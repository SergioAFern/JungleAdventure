<?php
// network manager version 0.1.0 by Sergio Fernandez july 28, 2015
// make sure all request are from the same domain... to avoid hacking.
 session_start();
 $return ='';
 // The Network Class
 class TNetwork {
   public $timeout = 500; // if an user has not communicated with the network for over $timeout time, he gets deleted from the message
   public $online = array(); // list of online users
   public $message = array(); // list of messages broadcasted  to individual users
 }
 class TMessage {
   public $sender;
   public $command;
   public $data;
   public $when;
 }

 if (!isset($_SESSION[$_GET['Domain'].'-'.$_GET['App']])) { // this variable should be saved to a file an the end, and loaded from the file at the beginning of this script.
  $_SESSION[$_GET['Domain'].'-'.$_GET['App']]= new TNetwork;
 }

// load $_SESSION[$_GET['Domain'].'-'.$_GET['App']] from file.
 if (file_exists('./data/'.$_GET['Domain'].'-'.$_GET['App'])) {

    unset($_SESSION[$_GET['Domain'].'-'.$_GET['App']]);
    $_SESSION[$_GET['Domain'].'-'.$_GET['App']]= new TNetwork;
    
    $data=json_decode(file_get_contents('./data/'.$_GET['Domain'].'-'.$_GET['App'],true));
    foreach($data->online as $key => $value) {
      $_SESSION[$_GET['Domain'].'-'.$_GET['App']]->online[$key]=$value;
    }
    foreach($data->message as $key => $value) {
      foreach($value as $key2 => $value2) {
        $_SESSION[$_GET['Domain'].'-'.$_GET['App']]->message[$key][$key2]=$value2;
      }
    }
 }

 if (isset($_SESSION["ID"])) { // keep track of last log in.
    $_SESSION[$_GET['Domain'].'-'.$_GET['App']]->online[$_SESSION["ID"]]=date('YmdHis');
 }

 switch ($_GET['command']) {
   case "CREATE":
      $_SESSION["ID"]=uniqid();
      $_SESSION[$_GET['Domain'].'-'.$_GET['App']]->online[$_SESSION["ID"]]=date('YmdHis');
      echo $_SESSION["ID"];
   break;
   case "DEBUG": // return the network time
      echo json_encode($_SESSION[$_GET['Domain'].'-'.$_GET['App']]);
   break;
   case "GET": // retreive message send to a specific user or to all users
      // check for user specific message
      if (array_key_exists($_GET['user'],$_SESSION[$_GET['Domain'].'-'.$_GET['App']]->message)) {       
         $return=$return.json_encode($_SESSION[$_GET['Domain'].'-'.$_GET['App']]->message[$_GET['user']]);
         $_SESSION[$_GET['Domain'].'-'.$_GET['App']]->message[$_GET['user']]=array();
      }
      echo $return;
   break;
   case "READ":
       $return='';
       if (file_exists('./data/'.$_GET['App'].'::'.$_GET['data'])) {
        $return =json_decode(file_get_contents('./data/'.$_GET['App'].'::'.$_GET['data'],true));
       };
       echo $return;
   break;
   case "WRITE":
      if (file_put_contents('./data/'.$_GET['App'].'::'.$_GET['user'],json_encode($_GET['data']))==false)
      {
        echo ' error saving data.';
      }
   break;
   default: 
    if ($_GET['user']!=0) { // send a message to specific member
      if (!isset($_SESSION[$_GET['Domain'].'-'.$_GET['App']]->message[$_GET['user']])) {
       $_SESSION[$_GET['Domain'].'-'.$_GET['App']]->message[$_GET['user']] = array();
      }
      $message = new TMessage;
      $message->sender=$_GET['sender'];
      $message->command=$_GET['command']; //send a message to an individual user.
      $message->data=$_GET['data'];
      $message->when=date('YmdHis');
      // can have multiple messages
      array_push($_SESSION[$_GET['Domain'].'-'.$_GET['App']]->message[$_GET['user']],$message);
    } else { // send a message to all members of this application       
        $message = new TMessage;
        $message->sender=$_GET['sender'];
        $message->command=$_GET['command']; //send a message to all users in App.
        $message->data=$_GET['data'];
        $message->when=date('YmdHis');

      foreach($_SESSION[$_GET['Domain'].'-'.$_GET['App']]->online as $user=>$value) {
        // NOTE: check that an user on list list has communicated in less then $timeout time, if not delete user.
        // can have multiple messages
        if ($user!=$_SESSION["ID"]) {
          if(($message->when-$value)>$_SESSION[$_GET['Domain'].'-'.$_GET['App']]->timeout) {
           unset($_SESSION[$_GET['Domain'].'-'.$_GET['App']]->online[$user]);
           unset($_SESSION[$_GET['Domain'].'-'.$_GET['App']]->message[$user]);
          } else {
            $_SESSION[$_GET['Domain'].'-'.$_GET['App']]->message[$user][$message->when]=$message;
          }
        }
      }
    }
 }

 // save $_SESSION[$_GET['Domain'].'-'.$_GET['App']] to file. 
 if (file_put_contents('./data/'.$_GET['Domain'].'-'.$_GET['App'],json_encode($_SESSION[$_GET['Domain'].'-'.$_GET['App']]))==false)
 {
   echo ' error creating session '.'./data/'.$_GET['Domain'].'-'.$_GET['App'];
 }
 // unset($_SESSION[$_GET['Domain'].'-'.$_GET['App']]); // sometime sI need to erase this variable
?>

