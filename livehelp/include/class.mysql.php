<?php
/*
stardevelop.com Live Help
International Copyright stardevelop.com

You may not distribute this program in any manner,
modified or otherwise, without the express, written
consent from stardevelop.com

You may make modifications, but only for your own 
use and within the confines of the License Agreement.
All rights reserved.

Selling the code for this program without prior 
written consent is expressly forbidden. Obtain 
permission before redistributing this program over 
the Internet or in any other medium.  In all cases 
copyright and header must remain intact.  
*/

error_reporting('E_ALL');

// User defined error handling function 
function userErrorHandler ($errno, $errmsg, $filename, $linenum, $vars) { 

	// Define an assoc array of error string 
	// in reality the only entries we should 
	// consider are 2,8,256,512 and 1024
	$errortype = array ( 
		1   =>  "Error", 
		2   =>  "Warning", 
		4   =>  "Parsing Error", 
		8   =>  "Notice", 
		16  =>  "Core Error", 
		32  =>  "Core Warning", 
		64  =>  "Compile Error", 
		128 =>  "Compile Warning", 
		256 =>  "User Error", 
		512 =>  "User Warning", 
		1024=>  "User Notice",
		2048=>	"Strict Error",
		4096=>	"Recoverable Error",
		8191=>	"All Errors"
		);

	$date = date("Y-m-d H:i:s"); 
	$trace = debug_backtrace();
	
	$file = ''; $line = ''; $function = '';
	foreach ($trace as $key => $value) {
		if (is_array($value)) {
			$file = $value['file'];
			$line = $value['line'];
			if (isset($value['function'])) { $function = $value['function']; }
		}
	}
	$error = "$date PHP {$errortype[$errno]}: $errmsg $filename at line $linenum (Debug Trace: $function() at line $line within $file)\n"; 

	// Save to the error log
	if (is_writable('../log/ERRORLOG.TXT')) { error_log($error, 3, '../log/ERRORLOG.TXT'); }

} 

set_error_handler('userErrorHandler'); 

class MySQL {

	var $db_host = DB_HOST;
	var $db_user = DB_USER;
	var $db_pass = DB_PASS;
	var $db_name = DB_NAME;
	var $db;
	var $db_result;
	var $db_error;
	
	function MySQL() {	
	}
	
	function connect() {
		//
		// Connects to the SQL server and sets the active database.
		//
		$this->connected = 1;
		if (function_exists('mysqli_connect')) {
			$this->db = mysqli_connect($this->db_host, $this->db_user, $this->db_pass) or $this->connected = 0;
		}
		else {
			$this->db = mysql_connect($this->db_host, $this->db_user, $this->db_pass) or $this->connected = 0;
		}
		$this->setdb();
		$this->setcharset();
	}
	
	function disconnect() {
		//
		// Connects to the SQL server and sets the active database.
		//
		$this->connected = 0;
		if (function_exists('mysqli_connect')) {
			$this->db = mysqli_close($this->db) or $this->connected = 1;
		}
		else {
			$this->db = mysql_close($this->db) or $this->connected = 1;
		}
	}
	
	function setdb($new_db = '') {
		//
		// Sets the active database.  If new_db is specified, the active database is set to it.
		// If not, it uses the current this->db_name.
		//
		if ($new_db) { $this->db_name = $new_db; }
		if (function_exists('mysqli_connect')) {
			if ($this->connected) { mysqli_select_db($this->db, $this->db_name); }
		}
		else {
			if ($this->connected) { mysql_select_db($this->db_name, $this->db); }
		}
	}

	function setcharset() {
		//
		// Sets the local client charset
		//
		if (function_exists('mysqli_set_charset')) {
			if ($this->connected) { mysqli_set_charset($this->db, 'utf8'); }
		}
		else if (function_exists('mysql_set_charset')) {
			if ($this->connected) { mysql_set_charset('utf8', $this->db); }
		}
		else {
			$this->miscquery('SET NAMES utf8');
		}
	}

	function seterror($sql, $error) {
		//
		// Called internally to set the error message generated by a failed method call.
		//
		trigger_error('SQL Error: ' . $sql . ' ' . $error, E_USER_ERROR); 
		$this->db_error = $error;
	}
	
	function insertquery($sql) {
		//
		// Wrapper for mysql_query(), for use with "INSERT INTO" queries.
		//
		// Returns the ID of the new row on success, or FALSE on error.
		//
		if (function_exists('mysqli_connect')) {
			$result = @mysqli_query($this->db, $sql) or $this->seterror($sql, mysqli_error($this->db));
			if ($result) {
				$this->affected = mysqli_affected_rows($this->db);
				return mysqli_insert_id($this->db);
			} else {
				return $result; 
			}
		}
		else {
			$result = @mysql_query($sql, $this->db) or $this->seterror($sql, mysql_error($this->db));
			if ($result) {
				$this->affected = mysql_affected_rows();
				return mysql_insert_id($this->db);
			} else {
				return $result; 
			}
		}
	}

	function deletequery($sql) {
		//
		// Wrapper for mysql_query(), for use with update queries.
		//
		// Basically just for consistency with updatequery().
		// Doesn't return FALSE on affected rows
		//
		if (function_exists('mysqli_connect')) {
			$result = (@mysqli_query($this->db, $sql) or $this->seterror($sql, mysqli_error($this->db)));
			if ($result) {
				return true;
			} else {
				return false;
			}
		}
		else {
			$result = (@mysql_query($sql, $this->db) or $this->seterror($sql, mysql_error($this->db)));
			if ($result) {
				return true;
			} else {
				return false;
			}
		}
	}

	function updatequery($sql) {
		//
		// Wrapper for mysql_query(), for use with update queries.
		//
		// Basically just for consistency with insertquery and() selectquery().
		// Returns the number of affected rows, or FALSE on failure.
		//
		if (function_exists('mysqli_connect')) {
			$result = (@mysqli_query($this->db,$sql) or $this->seterror($sql, mysqli_error($this->db)));
			if ($result) {
				$this->affected = mysqli_affected_rows($this->db);
				if  ($this->affected == 0) {
					return false;
				}
				return true;
			} else {
				return false;
			}
		}
		else {
			$result = (@mysql_query($sql, $this->db) or $this->seterror($sql, mysql_error($this->db)));
			if ($result) {
				$this->affected = mysql_affected_rows();
				if  ($this->affected == 0) {
					return false;
				}
				return true;
			} else {
				return false;
			}
		}
		
	}
	
	function miscquery($sql) {
		//
		// Wrapper for mysql_query(), for use with miscellaneous queries.
		//
		// Basically just for consistency with insertquery and() selectquery().
		// Doesn't return FALSE on affected rows
		//
		if (function_exists('mysqli_connect')) {
			$result = (@mysqli_query($this->db, $sql) or $this->seterror($sql, mysqli_error($this->db)));
			if ($result) {
				return true;
			} else {
				return false;
			}
		}
		else {
			$result = (@mysql_query($sql, $this->db) or $this->seterror($sql, mysql_error($this->db)));
			if ($result) {
				return true;
			} else {
				return false;
			}
		}
		
	}
	
	function selectquery($sql) {
		//
		// Wrapper for mysql_query(), for use with SELECT queries.
		//
		// Returns the first result row on success, or FALSE on failure.
		// Subsequent rows may be retrieved using selectnext().
		//
		if (function_exists('mysqli_connect')) {
			$result = @mysqli_query($this->db,$sql) or $this->seterror($sql, mysqli_error($this->db));
			if ($result) {
				$this->db_result = $result;
				$this->results = mysqli_num_rows($result);
				return $this->selectnext();
			} else {
				return $result; 
			}
		}
		else {
			$result = @mysql_query($sql,$this->db) or $this->seterror($sql, mysql_error($this->db));
			if ($result) {
				$this->db_result = $result;
				$this->results = mysql_num_rows($result);
				return $this->selectnext();
			} else {
				return $result; 
			}
		}
	}

	function selectnext() {
		//
		// Wrapper for mysql_fetch_assoc().
		//
		// Automatically strips escape characters (slashes) from string-type elements
		// prior to returning.
		// Returns the next result row on success, or FALSE on failure.
		//
		if (function_exists('mysqli_connect')) {
			$row = mysqli_fetch_assoc($this->db_result);
		}
		else {
			$row = mysql_fetch_assoc($this->db_result);
		}
		return $row;
	}
	
	function selectall($sql) {
		//
		// Executes a select query and returns ALL result rows for that query.
		//
		$output = array();
		$res = $this->selectquery($sql);
		if (!is_array($res)) { return false; }
		while (is_array($res)) {
			$output[] = $res;
			$res = $this->selectnext();
		}
		return $output;
	}
	
	function escape($string) {
		//
		// Wrapper for mysql_real_escape_string.
		//
		// Automatically escapes string
		// Returns the escaped string.
		//
		if (function_exists('mysqli_connect')) {
			$escaped = mysqli_real_escape_string($this->db, $string);
		}
		else {
			$escaped = mysql_real_escape_string($string, $this->db);
		}
		return $escaped;
	}

}
?>