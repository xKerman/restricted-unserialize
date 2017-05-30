<?php
// see: https://github.com/nikic/PHP-Parser/blob/master/doc/2_Usage_of_basic_components.markdown

require __DIR__ . '/../vendor/autoload.php';

use PhpParser\BuilderFactory;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;

class NameSpaceConverter extends \PhpParser\NodeVisitorAbstract
{
    public function leaveNode(Node $node) {
        if ($node instanceof Node\Name) {
            return new Node\Name(str_replace('\\', '_', $node->toString()));
        }
        if ($node instanceof Stmt\Class_ ||
            $node instanceof Stmt\Interface_ ||
            $node instanceof Stmt\Function_) {
            $node->name = str_replace('\\', '_', $node->namespacedName->toString());
        }
        if ($node instanceof Stmt\Const_) {
            foreach ($node->consts as $const) {
                $const->name = str_replace('\\', '_', $const->namespacedName->toString());
            }
        }
        if ($node instanceof Stmt\Namespace_) {
            return $node->stmts;
        }
        if ($node instanceof Stmt\Use_) {
            return NodeTraverser::REMOVE_NODE;
        }
        if ($node instanceof Stmt\ClassMethod) {
            $doc = $node->getDocComment();
            if (is_null($doc)) {
                return $node;
            }

            $text = preg_replace('/\\\\xKerman\\\\Restricted\\\\([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff])/', 'xKerman_Restricted_$1', $doc->getText());
            $text = preg_replace('/ @covers ::(?:[a-zA-Z_][a-zA-Z0-9_]*)/', '', $text);

            $search = [
                '\InvalidArgumentException',
            ];
            $replace = [
                'InvalidArgumentException',
            ];
            $newDoc = new Comment\Doc(
                str_replace($search, $replace, $text),
                $doc->getLine(),
                $doc->getFilePos()
            );
            $node->setAttribute('comments', [$newDoc]);
        }
    }
}

function convert($inDir, $outDir)
{
    $factory = new ParserFactory();
    $parser = $factory->create(ParserFactory::ONLY_PHP5);
    $traverser = new NodeTraverser();
    $printer = new PrettyPrinter\Standard();

    $traverser->addVisitor(new NameResolver());
    $traverser->addVisitor(new NameSpaceConverter());

    $files = new \RecursiveIteratorIterator(new RecursiveDirectoryIterator($inDir));
    $files = new \RegexIterator($files, '/\.php\z/');

    if (!file_exists($outDir)) {
        mkdir($outDir, 0755, true);
    }

    foreach ($files as $file) {
        try {
            $code = file_get_contents($file);
            $statements = $parser->parse($code);
            $statements = $traverser->traverse($statements);
            $sep = DIRECTORY_SEPARATOR;
            file_put_contents(
                "{$outDir}{$sep}{$file->getFileName()}",
                $printer->prettyPrintFile($statements)
            );
        } catch (PhpParser\Error $e) {
            echo 'Parsee Error: ', $e->getMessage();
        }
    }
}

function generateBootstrap($dir)
{
    $code = <<<'PHPCODE'
<?php

function xKerman_Restricted_bootstrap($classname)
{
    if (strpos($classname, 'xKerman_Restricted_') !== 0) {
        return false;
    }
    $sep = DIRECTORY_SEPARATOR;
    $namespace = explode('_', $classname);
    $filename = array_pop($namespace);
    $path = dirname(__FILE__) . "{$sep}{$filename}.php";
    if (file_exists($path)) {
        require_once $path;
    }
}

spl_autoload_register('xKerman_Restricted_bootstrap');
$sep = DIRECTORY_SEPARATOR;
require_once dirname(__FILE__) . "{$sep}function.php";

PHPCODE;

    $sep = DIRECTORY_SEPARATOR;
    file_put_contents(
        "{$dir}{$sep}bootstrap.php",
        $code
    );
}

// main
convert(__DIR__ . '/../src', __DIR__ . '/../generated/src/xKerman/Restricted');
convert(__DIR__ . '/../test', __DIR__ . '/../generated/test/xKerman/Restricted');
generateBootstrap(__DIR__ . '/../generated/src/xKerman/Restricted');
