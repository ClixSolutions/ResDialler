<?

  // mySQL Database Class
  //
  // Tim Igoe (tim@timigoe.co.uk)

  class Database_Mysql #extends Database
  {
    protected $hostname;
	  protected $database;
	  protected $username;
	  protected $password;
    protected $newlink;
  	protected $connection;
    protected $cache;

    // Constructor - set the database options
    public function __construct($config)
    {
      $this->hostname = $config['hostname'];
      if ($config['port'])
        $this->hostname .= ':' . $config['port'];
      $this->database = $config['database'];
      $this->username = $config['username'];
      $this->password = $config['password'];
      if ($config['newlink'])
        $this->newlink = true;
      else
        $this->newlink = false;
	    $this->connection = 0;
      $this->cache = array();

	    $this->connect();
    }

  	// Destructor
	  public function __destruct()
	  {
	    $this->disconnect();
	  }

    // Connects to the database
    public function connect()
    {
      $this->connection = mysql_connect($this->hostname, $this->username, $this->password, $this->newlink);

	    if (!$this->connection && !$this->autoreconnect && $this->reconnectLimit > 5) // We've failed 5 reconnections
	      die('Failed to connect to ' . $this->database);#throw new DatabaseException($this->getError());
      else if (!$this->connection && $this->autoreconnect)
      {
        sleep(5);
        $this->reconnectLimit++;
        $this->connect();
      }

      $this->reconnectLimit = 0;

	    $this->selectdb();
    }

    // Selects the database to use - set at the start in the constructor
    public function selectdb()
	  {
	    @mysql_select_db($this->database, $this->connection);
	  }

    // Disconnects the databases
    public function disconnect()
    {
      @mysql_close($this->connection);
	  }

    // Read from a database
    public function query_read($query, $throw = true)
    {
	    $result = @mysql_query($query, $this->connection);

      if (!$result && $this->autoreconnect && $this->getError(true) == 2006)
      {
        $this->disconnect();
        $this->connect();
        return $this->query_read($query, $throw);
      }
	    else if (!$result && $throw)
	      die($this->getError());#throw new DatabaseException($this->getError() . "<br /><br />" . $query, 1);
	    else if (!$result && !$throw)
	      return -1;
      return $result;
    }

    // Reads and caches a query into memcached
    public function query_read_cached($query, $throw = true, $life = 600)
    {
      $md5sum = md5($query);

      // Make the check to memcache
      $MC = Framework::getFramework()->getMemcache();

      if ($MC && $MC->get($md5sum))
      { // Load the results from the query cache
        $this->cached[$md5sum] = array('data' => $MC->get($md5sum), 'row' => 0);;
      }
      else if ($MC)
      {
        // Load up the results from the database, and save them into the query cache
        $result = $this->query_read($query, $throw);

        // Load it all up here
        $cache = array();
        while ($row = $this->fetch_row($result))
        {
          $cache[] = $row;
        }
        $this->cached[$md5sum] = array('data' => $cache, 'row' => 0);

        // Save them to memcache
        $MC->add($md5sum, $cache, $life);
      }
      else
      {
        return $this->query_read($query, $throw);
      }

      return $md5sum;
    }

    // Reads from a database and returns the first row
    public function query_first($query, $type = MYSQL_ASSOC, $throw = true)
    {
      if ($type == null)
        $type = MYSQL_ASSOC;

      $result = $this->query_read($query, $throw);
      if ($result <= 0 && !$throw)
	      return $result;
	    else
        return $this->fetch_row($result, $type);;
    }

	  // Reads a row from a database
    public function fetch_row($result, $type = MYSQL_ASSOC)
    {
      if (is_array($this->cached[$result]))
        return $this->cached[$result]['data'][$this->cached[$result]['row']++];

	    return mysql_fetch_array($result, $type);
    }


	  // Reads from a database, and returns the first field of the first row of the result
	  // Useful for select count() type queries
	  public function get_val($query, $throw = true)
    {
      $result = $this->query_first($query, MYSQL_NUM, $throw);

      if ($result <= 0 && !$throw)
        return $result;
      else
        return $result[0];
    }

    // Write to a database, in normal use, this will be the only connection
    // in a replication environment this will ALWAYS want to be the master
    public function query_write($query, $throw = true)
    {
  	  $result = @mysql_query($query, $this->connection);

      if (!$result && $this->autoreconnect && $this->getError(true) == 2006)
      {
        $this->disconnect();
        $this->connect();
        return $this->query_read($query, $throw);
      }
      else if (!$result && $throw)
	      print $this->getError() . "<br /><br />" . $query;#throw new DatabaseException($this->getError() . "<br /><br />" . $query, 1);
	    else if (!$result && !$throw)
	      return -1;

      return $result;
    }

    // Returns the auto_increment ID of the last inserted row
    public function insert_id()
    {
      return mysql_insert_id($this->connection);
    }

    // Returns the number of rows in the result set
    public function num_rows($Result)
    {
      return mysql_num_rows($Result);
    }

	  // Number of fields in the result set
  	public function num_fields($Result)
	  {
	    return mysql_num_fields($Result);
	  }

	  // Field name
	  public function field_name($Result, $Field)
	  {
	    return mysql_field_name($Result, $Field);
	  }

    // Returns the number of affected rows of the last query
	  // (INSERT, UPDATE or DELETE)
	  public function affected_rows()
	  {
	    return mysql_affected_rows($this->connection);
	  }

	  // Returns the error from mySQL
	  public function getError($short = false)
	  {
	    if ($short)
	      return @mysql_errno($this->connection);
	    else
	      return @mysql_error($this->connection);
	  }
  }
?>