# Stmt
Prepared Statements wrapper for php.
Now you can use prepared statements just like a normal query.

##Example
Let's say for example a user tries to find another user by email.
We store it's requested email and filter it.
We store the result in the varaible $email.
We make a mysqli object and store it in $con.
One way to fetch our reuqested username would be:
```
$stmt = new Stmt($con, "SELECT * FROM ".USERS_TABLE." WHERE email=?", array(&$email)) or die($this->con->error);
    // check for result 
    if ($stmt->getNumRows() > 0) {
                $result = $stmt->fetchRow();
                $username = $result['username'];
    }
    ...
```
Just like a usual query.
