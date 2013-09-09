<?

  // mySQL Database Class
  //
  // Tim Igoe (tim@timigoe.co.uk)

  class Database_Mssql #extends Database
  {
    protected $hostname;
	  protected $database;
	  protected $username;
	  protected $password;
	  protected $connectionInfo;
  	protected $connection;

    // Constructor - set the database options
    public function __construct($config)
    {
      $this->hostname = $config['hostname'];
      $this->database = $config['connectionInfo']['database'];
      $this->username = $config['connectionInfo']['UID'];
      $this->password = $config['connectionInfo']['PWD'];
      $this->connectionInfo = $config['connectionInfo'];
	    $this->connection = 0;

	    $this->connect();

	    // Remove the password - its no longer needed
	    unset($this->password);
    }

  	// Destructor
	  public function __destruct()
	  {
	    $this->disconnect();
	  }

    // Connects to the database
    public function connect()
    {
      $this->connection = sqlsrv_connect($this->hostname, $this->connectionInfo);
	    if (!$this->connection)
	      die('Failed to connect to ' . $this->connectionInfo['Database']);#throw new DatabaseException($this->getError());

	    $this->selectdb();
    }

    // Selects the database to use - set at the start in the constructor
    public function selectdb($database = false)
	  {
	  	if(!$database)
	  	  $database = $this->database; 
	  	  
	    sqlsrv_query($this->connection, 'USE ' . $database);
	  }

    // Disconnects the databases
    public function disconnect()
    {
      sqlsrv_close($this->connection);
	  }

    // Read from a database
    public function query_read($query, $throw = true)
    {
	    $result = sqlsrv_query($this->connection, $query);

	    if (!$result && $throw)
	    {
	    	print $query;
	      print_r($this->getError()); #throw new DatabaseException($this->getError() . "<br /><br />" . $query, 1);
       }
	    else if (!$result && !$throw)
	      return -1;
      return $result;
    }

    // Reads from a database and returns the first row
    public function query_first($query, $type = SQLSRV_FETCH_ASSOC, $throw = true)
    {
      $result = $this->query_read($query);
      
      if ($result <= 0) // Error
        return $result;
      else
        return $this->fetch_row($result, $type);
    }
   
    // Reads a row from a database
    public function fetch_row($result, $type = SQLSRV_FETCH_ASSOC)
    {
	    return @sqlsrv_fetch_array($result, $type);
    }


	  // Reads from a database, and returns the first field of the first row of the result
	  // Useful for select count() type queries
	  public function get_val($query, $throw = true)
    {
      $result = $this->query_first($query, SQLSRV_FETCH_NUMERIC, $throw);
      
      if (!$throw)
        return $result;
      else
        return $result[0];
    }

    // Write to a database, in normal use, this will be the only connection
    // in a replication environment this will ALWAYS want to be the master
    public function query_write($query, $throw = true)
    {
  	  $result = sqlsrv_query($this->connection, $query);

  	  if (!$result && $throw)
  	  {
	      #print_r($this->getError()); #throw new DatabaseException($this->getError() . "<br /><br />" . $query, 1);
	      #print "<pre>" . $query . "</pre>";
      }
	    else if (!$result && !$throw)
	      return -1;

      return $result;
    }

    // Returns the auto_increment ID of the last inserted row
    public function insert_id()
    {
      return $this->get_val("@@IDENTITY");
    }

    // Returns the number of rows in the result set
    public function num_rows($Result)
    {
      return mssql_num_rows($Result);
    }

	  // Number of fields in the result set
  	public function num_fields($Result)
	  {
	    return mssql_num_fields($Result);
	  }

	  // Field name
	  public function field_name($Result, $Field)
	  {
	    return mssql_field_name($Result, $Field);
	  }

    // Returns the number of affected rows of the last query
	  // (INSERT, UPDATE or DELETE)
	  public function affected_rows()
	  {
	    return sqlsrv_rows_affected($this->connection);
	  }

	  // Returns the error from mySQL
	  public function getError($short = false)
	  {	  	
	  	if(($errors = sqlsrv_errors()) != null)
	  	{
	  		foreach($errors as $error)
	  		{
	  			$errorMessage[] = str_replace('[Microsoft][SQL Server Native Client 10.0][SQL Server]', false, $error['message']);
	  		}
	  	}
	  	
	  	return $errorMessage;
	  }
  }
?>