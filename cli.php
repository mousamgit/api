<?php

if (PHP_SAPI !== 'cli') {
    die("This script can only be run from the command line.");
}

$command = isset($argv[1]) ? $argv[1] : '';

switch ($command) {
    case 'make:model':
        makeModel($argv);
        break;
    case 'make:controller':
        makeController($argv);
        break;
    default:
        echo "Command not found.\n";
        echo "You can try for these:\n";
        echo "make:model\n";
        echo "make:controller\n";
        break;
}

function makeModel($args) {
    $modelName = isset($args[2]) ? ucfirst($args[2]) : '';

    $content = "<?php\n\n";
    $content .= "namespace Models;\n\n";
    $content .= "use Illuminate\Database\Eloquent\Model;\n\n";
    $content .= "class {$modelName} extends Model\n";
    $content .= "{\n";
    $content .= "    // Your model logic here\n";
    $content .= "}\n";

    $fileName = __DIR__ . "/models/{$modelName}.php";
    file_put_contents($fileName, $content);

    echo "Model '{$modelName}' created successfully.\n";
}

function makeController($args) {

    $controllerName = isset($args[2]) ? ucfirst($args[2]) : '';

    $content = "<?php\n\n";
    $content .= "namespace Controllers;\n\n";
    $content .= "class {$controllerName}\n";
    $content .= "{\n";
    $content .= "    // Your controller logic here\n";
    $content .= "}\n";

    $fileName = __DIR__ . "/controllers/{$controllerName}.php";
    file_put_contents($fileName, $content);

    echo "Controller '{$controllerName}' created successfully.\n";
}
