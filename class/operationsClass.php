<?php
// require 'db.php';

require 'conectDB.php';
class Operations extends connectDB{
    public $session_user_id = '';
    public $session_wallet_bal = '';
    public $phone_number = '';
    public $nickname = '';
    public $game_id = '';
    // public $session_pg = 0;
    public $session_phone_number = '';
	// public $session_others = '';
    public $thisState=0;
    
	
    public function setSessions($sessions) {
        // global $connection;
        // Check if gamer exist
        $gamer = "SELECT * FROM `users` WHERE game_id = '".$sessions['game_id']."'";
        $gamers_sessions = mysqli_query($this->doConnect(), $gamer);
        $fet_sessions = mysqli_fetch_array($gamers_sessions);
        // print_r($fet_sessions);
        // exit;
        if(mysqli_num_rows($gamers_sessions) > 0){
            $updtRec = "UPDATE `users` SET `nickname`= '".$fet_sessions['nickname']."',`user_id`='".$fet_sessions['user_id']."',`game_id`='".$fet_sessions['game_id']."',`wallet_bal`='".$fet_sessions['wallet_bal']."',`phone_number`='".$fet_sessions['phone_number']."' WHERE game_id = '".$sessions['game_id']."'";

            $quy_sessions = mysqli_query($this->doConnect(), $updtRec);
        }
        else{
            $sql_sessions = "INSERT INTO `users` (`user_id`, `phone_number`, `menu`, `game_id`) VALUES 
			('" . $sessions['user_id'] . "', '" . $sessions['phone_number'] . "', '" . $sessions['menu'] . "', '" . $sessions['game_id'] . "')";
// if you have errors in here check sql query near " CURRENT_TIMESTAMP ".
            $quy_sessions = mysqli_query($this->doConnect(), $sql_sessions);
        }
	}
	




    public function getSession($sessionid) {
        $sql_session = "SELECT *  FROM  `users` WHERE  user_id='" . $sessionid . "'";
        $quy_sessions = mysqli_query($this->doConnect(), $sql_session);
        $fet_sessions = mysqli_fetch_array($quy_sessions);
        $this->session_others = $fet_sessions['game_id'];
        return $fet_sessions;
	}
	
	
    public function saveSesssion() {
        $sql_session = "UPDATE  `users` SET 
									`user_id` =  '" . $this->session_user_id . "',
									`wallet_bal` =  '" . $this->session_wallet_bal . "',
									`phone_number` =  '" . $this->phone_number . "',
                                    `nickname` = '" . $this->nickname . "',
                                    `game_id` = '" . $this->game_id . "'
									WHERE `user_id` =  '" . $this->session_user_id . "'";
        $quy_sessions = mysqli_query($this->doConnect(), $sql_session);
    }
    public function endSesssion() {
        session_destroy();
    }
}
?>