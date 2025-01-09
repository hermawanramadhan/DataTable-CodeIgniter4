<?php
namespace Hermawan\DataTables;

use \Config\Services;

class DataTable
{

    /**
     * DataTableQuery object.
     *
     * @var DataTableQuery
     */
    private $query;


    /**
     * DataTablColumns object.
     *
     * @var DataTableColumnDefs
     */
    private $columnDefs;


    /**
     * Builder from CodeIgniter Query Builder
     * @param  \CodeIgniter\Database\BaseBuilder| \CodeIgniter\BaseModel $builder
     */
    public function __construct($builder)
    {
        if (is_subclass_of($builder, '\CodeIgniter\BaseModel') && method_exists($builder, 'builder')) {
            $builder = $builder->builder();
        }
        $this->query = new DataTableQuery($builder);
        $this->columnDefs = new DataTableColumnDefs($builder);
    }

    /**
     * Make a DataTable instance from builder.
     *  
     * Builder from CodeIgniter Query Builder
     * @param  \CodeIgniter\Database\BaseBuilder| \CodeIgniter\BaseModel $builder
     */
    public static function of($builder): DataTable
    {
        return new self($builder);
    }

    /**
     * postQuery
     * @param Closure $postQuery
     */
    public function postQuery($postQuery)
    {
        $this->query->postQuery($postQuery);
        return $this;
    }

    /**
     * custom Filter 
     * @param Closure function
     */
    public function filter($filterFunction)
    {
        $this->query->filter($filterFunction);
        return $this;
    }


    /**
     * Add numbering to first column
     * @param string $column 
     */
    public function addNumbering($column = NULL)
    {
        $this->columnDefs->addNumbering($column);
        return $this;
    }


    /**
     * Add extra column 
     *
     * @param string $column
     * @param Closure $callback
     * @param string|int $position
     */
    public function add($column, $callback, $position = 'last')
    {
        $this->columnDefs->add($column, $callback, $position);
        return $this;
    }

    /**
     * Edit column 
     *
     * @param string $column
     * @param Closure $callback
     */
    public function edit($column, $callback)
    {
        $this->columnDefs->edit($column, $callback);
        return $this;
    }

    /**
     * Format column 
     *
     * @param string $column
     * @param Closure $callback
     */
    public function format($column, $callback)
    {
        $this->columnDefs->format($column, $callback);
        return $this;
    }

    /**
     * Hide column 
     *
     * @param string $column
     */
    public function hide($column)
    {
        $this->columnDefs->remove($column);
        return $this;
    }

    /**
     * Escape column
     * 
     * @param string $column
     * @param bool $escape
     */
    public function escape($column, $escape = TRUE)
    {
        $this->columnDefs->escape($column, $escape);
        return $this;
    }

    /**
     * Set Searchable columns
     * @param string|array
     */
    public function setSearchableColumns($columns)
    {
        $this->columnDefs->setSearchable($columns);
        return $this;
    }

    /**
     * Add Searchable columns
     * @param string|array
     */
    public function addSearchableColumns($columns)
    {
        $this->columnDefs->addSearchable($columns);
        return $this;
    }



    /**
     * Return JSON output 
     *
     * @param bool $returnAsObject
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function toJson($returnAsObject = NULL)
    {

        if (!Request::get('draw')) {
            return self::throwError('no datatable request detected');
        }

        if ($returnAsObject !== NULL)
            $this->columnDefs->returnAsObject($returnAsObject);

        $this->query->setColumnDefs($this->columnDefs);

        $response = Services::response();

        return $response->setJSON([
            'draw' => Request::get('draw'),
            'recordsTotal' => $this->query->countAll(),
            'recordsFiltered' => $this->query->countFiltered(),
            'data' => $this->query->getDataResult(),

        ]);
    }


    /**
     * Throw Error
     *
     * @param string $message
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public static function throwError($message)
    {
        $response = Services::response();
        return $response->setJSON([
            'error' => $message,

        ]);
    }



}   // End of DataTables Library Class.
