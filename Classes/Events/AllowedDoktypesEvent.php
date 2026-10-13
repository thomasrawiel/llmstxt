<?php
declare(strict_types=1);

namespace LINGNER\Llmstxt\Events;

class AllowedDoktypesEvent implements \Stringable
{
    public function __construct(private array $allowedDoktypes)
    {

    }

    public function getAllowedDoktypes(): array
    {
        return $this->allowedDoktypes;
    }

    public function setAllowedDoktypes(array $allowedDoktypes): void
    {
        $this->allowedDoktypes = $allowedDoktypes;
    }

    public function addDoktype(int $doktype): void
    {
        if (!in_array($doktype, $this->allowedDoktypes)) {
            $this->allowedDoktypes[] = $doktype;
        }
    }

    public function removeDoktype(int $doktype): void
    {
        $key = array_search($doktype, $this->allowedDoktypes, true);
        if ($key !== false) {
            unset($this->allowedDoktypes[$key]);
            $this->allowedDoktypes = array_values($this->allowedDoktypes);
        }
    }

    public function __toString(): string
    {
        return implode(', ', $this->allowedDoktypes);
    }
}
