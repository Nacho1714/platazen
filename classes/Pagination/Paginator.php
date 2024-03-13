<?php

namespace App\Pagination;

class Paginator
{
    protected int $recordsPerPage;
    protected int $initialRecord;
    protected int $totalRecords;
    protected int $page;
    protected int $pages;

    public function __construct(int $recordsPerPage)
    {
        $this->recordsPerPage = $recordsPerPage;
        $this->page = $_GET['p'] ?? 1;
        $this->initialRecord = $this->recordsPerPage * ($this->page - 1);
    }









    // Getters and Setters
    public function getRecordsPerPage(): int
    {
        return $this->recordsPerPage;
    }

    public function setRecordsPerPage(int $recordsPerPage): void
    {
        $this->recordsPerPage = $recordsPerPage;
    }

    public function getInitialRecord(): int
    {
        return $this->initialRecord;
    }

    public function setInitialRecord(int $initialRecord): void
    {
        $this->initialRecord = $initialRecord;
    }

    public function getTotalRecords(): int
    {
        return $this->totalRecords;
    }

    public function setTotalRecords(int $totalRecords): void
    {
        $this->totalRecords = $totalRecords;
        $this->pages = ceil($totalRecords / $this->recordsPerPage);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function setPages(int $pages): void
    {
        $this->pages = $pages;
    }


}