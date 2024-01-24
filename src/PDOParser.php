<?php

namespace tebe;

use PDOException;

/**
 * Copied from https://github.com/auraphp/Aura.Sql and simplified.
 */
class PDOParser
{
    protected string $driver = '';

    protected array $split = [];

    protected string $skip = ''; //'/^(\'|\"|\:[^a-zA-Z_])/um';

    protected int $num = 0;

    protected array $count = [
        '__' => null,
    ];

    protected array $values = [];

    protected array $final_values = [];

    public function __construct(string $driver)
    {
        $this->driver = $driver;
        $this->split = $this->determineSplitRegex($driver);
        $this->skip = $this->determineSkipRegex($driver);
    }

    public function rebuild(string $statement, array $values = []): array
    {
        // match standard PDO execute() behavior of zero-indexed arrays
        if (array_key_exists(0, $values)) {
            array_unshift($values, null);
        }

        $this->values = $values;
        $statement = $this->rebuildStatement($statement);
        return [$statement, $this->final_values];
    }

    protected function rebuildStatement(string $statement): string
    {
        $parts = $this->getParts($statement);
        return $this->rebuildParts($parts);
    }

    protected function rebuildParts(array $parts): string
    {
        $statement = '';
        foreach ($parts as $part) {
            $statement .= $this->rebuildPart($part);
        }
        return $statement;
    }

    protected function rebuildPart(string $part): string
    {
        if (preg_match($this->skip, $part)) {
            return $part;
        }

        // split into subparts by ":name" and "?"
        $subs = preg_split(
            "/(?<!:)(:[a-zA-Z_][a-zA-Z0-9_]*)|(\?)/um",
            $part,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

        // check subparts to expand placeholders for bound arrays
        return $this->prepareValuePlaceholders($subs);
    }

    protected function prepareValuePlaceholders(array $subs): string
    {
        $str = '';
        foreach ($subs as $i => $sub) {
            $char = substr($sub, 0, 1);
            if ($char == '?') {
                $str .= $this->prepareNumberedPlaceholder();
            } elseif ($char == ':') {
                $str .= $this->prepareNamedPlaceholder($sub);
            } else {
                $str .= $sub;
            }
        }
        return $str;
    }

    protected function prepareNumberedPlaceholder()
    {
        $this->num ++;
        if (array_key_exists($this->num, $this->values) === false) {
            throw new PDOException("Parameter {$this->num} is missing from the bound values");
        }

        $expanded = [];
        $values = (array) $this->values[$this->num];
        if (is_null($this->values[$this->num])) {
            $values[] = null;
        }
        foreach ($values as $value) {
            $count = ++ $this->count['__'];
            $name = "__{$count}";
            $expanded[] = ":{$name}";
            $this->final_values[$name] = $value;
        }
        return implode(', ', $expanded);
    }

    protected function prepareNamedPlaceholder(string $sub): string
    {
        $orig = substr($sub, 1);
        if (array_key_exists($orig, $this->values) === false) {
            throw new PDOException("Parameter '{$orig}' is missing from the bound values");
        }

        $name = $this->getPlaceholderName($orig);

        // is the corresponding data element an array?
        $bind_array = is_array($this->values[$orig]);
        if ($bind_array) {
            // expand to multiple placeholders
            return $this->expandNamedPlaceholder($name, $this->values[$orig]);
        }

        // not an array, retain the placeholder for later
        $this->final_values[$name] = $this->values[$orig];
        return ":$name";
    }

    protected function getPlaceholderName(string $orig): string
    {
        if (! isset($this->count[$orig])) {
            $this->count[$orig] = 0;
            return $orig;
        }

        $count = ++ $this->count[$orig];
        return "{$orig}__{$count}";
    }

    protected function expandNamedPlaceholder(string $prefix, array $values): string
    {
        $i = 0;
        $expanded = [];
        foreach ($values as $value) {
            $name = "{$prefix}_{$i}";
            $expanded[] = ":{$name}";
            $this->final_values[$name] = $value;
            $i ++;
        }
        return implode(', ', $expanded);
    }

    protected function getParts(string $statement): array
    {
        $split = implode('|', $this->split);
        return preg_split(
            "/($split)/um",
            $statement,
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );
    }

    protected function determineSplitRegex($driver): array
    {
        // see https://github.com/auraphp/Aura.Sql/tree/5.x/src/Parser
        return match($driver) {
            'mysql' => [
                // single-quoted string
                "'(?:[^'\\\\]|\\\\'?)*'",
                // double-quoted string
                '"(?:[^"\\\\]|\\\\"?)*"',
                // backtick-quoted string
                '`(?:[^`\\\\]|\\\\`?)*`',                
            ],
            'pgsql' => [
                // single-quoted string
                "'(?:[^'\\\\]|\\\\'?)*'",
                // double-quoted string
                '"(?:[^"\\\\]|\\\\"?)*"',
                // double-dollar string (empty dollar-tag)
                '\$\$(?:[^\$]?)*\$\$',
                // dollar-tag string -- DOES NOT match tags properly
                '\$[^\$]+\$.*\$[^\$]+\$',
            ],
            'sqlite' => [
                // single-quoted string
                "'(?:[^'\\\\]|\\\\'?)*'",
                // double-quoted string
                '"(?:[^"\\\\]|\\\\"?)*"',
                // backticked column names
                '`(?:[^`\\\\]|\\\\`?)*`',
            ],
            default => [
                // single-quoted string
                "'(?:[^'\\\\]|\\\\'?)*'",
                // double-quoted string
                '"(?:[^"\\\\]|\\\\"?)*"',
            ]
        };
    }

    protected function determineSkipRegex($driver): string
    {
        return match($driver) {
            'mysql' => '/^(\'|\"|\`)/um',
            'pgsql' => '/^(\'|\"|\$|\:[^a-zA-Z_])/um',
            'sqlite' => '/^(\'|"|`|\:[^a-zA-Z_])/um',
            default => '/^(\'|\"|\:[^a-zA-Z_])/um'
        };
    }
}
