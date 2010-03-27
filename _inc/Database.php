<?php
class Database
{
	private static $hostname = 'localhost';
	private static $username = 'noemig_dream';
	private static $password = 'XVGBxMbTXahNUh2K';
	
	public function connect()
	{
		$dbh = mysql_connect(self::$hostname, self::$username, self::$password)
			or die("Unable to connect to MySQL");
			
		$db = mysql_select_db('noemig_dream', $dbh)
			or die("Unable to select the databse");
		
		return $dbh;
	}
	
	public function checkBan()
	{
		$db = self::connect();
		
		$mkendtimep=mktime(date("H")-1, date("i"), date("s"), date("m"), date("d"), date("Y"));
		$moment=date("Y-m-d H:i:s", $mkendtimep);
		
		$query = "SELECT COUNT(*) AS `count` FROM `dream` WHERE `timestamp` > '" . $moment . "' AND `ip` = '" . $_SERVER['REMOTE_ADDR'] . "'";
	
		$result = mysql_query($query);
		
		$row = mysql_fetch_assoc($result);
		
		return($row['count']);
	}
	
	public function checkBanComment()
	{
		$db = self::connect();
		
		$mkendtimep=mktime(date("H"), date("i")-5, date("s"), date("m"), date("d"), date("Y"));
		$moment=date("Y-m-d H:i:s", $mkendtimep);

		$query = "SELECT COUNT(*) AS `count` FROM `comment` WHERE `timestamp` > '" . $moment . "' AND `ip` = '" . $_SERVER['REMOTE_ADDR'] . "'";
	
		$result = mysql_query($query);
		
		$row = mysql_fetch_assoc($result);

		return($row['count']);
	}
	
	public function insertDream($title, $content, $ip)
	{
		$db = self::connect();
		
		$query = "INSERT INTO `dream` (`title`, `content`,`ip`) VALUES ('" . mysql_real_escape_string($title) . "', '" . mysql_real_escape_string($content) . "', '" . $ip . "')";
		
		return mysql_query($query);	
	}
	
	public function insertComment($content, $dream_id, $ip)
	{
		$db = self::connect();
		
		$query = "INSERT INTO `comment` (`content`, `dream_id`, `ip`) VALUES ('" . mysql_real_escape_string($content) . "', " . $dream_id . ", '" . $ip . "')";

		return mysql_query($query);		
	}
	
	public function getDreams()
	{
		$db = self::connect();

		$query = 'SELECT * FROM `dream` ORDER BY `timestamp` DESC';
		
		return mysql_query($query);
	}
	
	public function getComments()
	{
		$db = self::connect();

		$query = 'SELECT * FROM `comment` ORDER BY `timestamp` DESC';
		
		return mysql_query($query);
	}
}

?>