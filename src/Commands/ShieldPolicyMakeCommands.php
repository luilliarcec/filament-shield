<?php

namespace Luilliarcec\FilamentShield\Commands;

use Illuminate\Foundation\Console\PolicyMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ShieldPolicyMakeCommands extends PolicyMakeCommand
{
    protected $name = 'shield:policy';

    protected function buildClass($name): string
    {
        $resource = $this->option('resource');

        $stub = $this->generateBodyFromPermissions(parent::buildClass($name), $resource);

        $stub = $this->replaceUserNamespace($stub);

        $model = $this->option('model');

        return $this->replaceModel($stub, $model);
    }

    protected function generateBodyFromPermissions($stub, $resource): string
    {
        $stub_function = $this->files->get($this->getFunctionStub());

        $body = collect($resource::permissions())
            ->reduce(
                function ($body, $suffix) use ($stub_function, $resource) {
                    $parameters = $this->policyMethodRequireParameters($suffix)
                        ? ', {{ model }} $model'
                        : '';

                    $body .= str_replace(
                        ['{{ method }}', '{{ permission }}', '{{ parameters }}'],
                        [Str::camel($suffix), $resource::getPermissionName($suffix), $parameters],
                        $stub_function
                    );

                    return $body;
                },
                ""
            );

        return str_replace('{{ body }}', $body, $stub);
    }

    protected function getNamespace($name): string
    {
        return $this->rootNamespace().'\\Policies';
    }

    protected function getPath($name): string
    {
        return str($this->laravel['path'])->beforeLast(DIRECTORY_SEPARATOR).'/'.str_replace('\\', '/', $name).'.php';
    }

    protected function rootNamespace(): string
    {
        $model = str_replace('/', '\\', $this->option('model'));

        $model = trim($model, '\\');

        return str($model)->beforeLast('\\Models');
    }

    protected function userProviderModel(): ?string
    {
        $config = $this->laravel['config'];

        $provider = $config->get('auth.guards.'.$config->get('auth.defaults.guard').'.provider');

        return $config->get("auth.providers.{$provider}.model");
    }

    public function getStub(): string
    {
        return __DIR__.'/../stubs/policy.stub';
    }

    public function getFunctionStub(): string
    {
        return __DIR__.'/../stubs/policy_function.stub';
    }

    protected function getOptions(): array
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'The model that the policy applies to'],
            ['resource', 'r', InputOption::VALUE_REQUIRED, 'The resource that contains the permissions'],
            ['force', 'force', InputOption::VALUE_OPTIONAL, 'Force override of policies'],
        ];
    }

    protected function policyMethodRequireParameters($suffix): bool
    {
        return in_array($suffix, ['view', 'update', 'delete', 'restore']);
    }
}
