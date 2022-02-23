<?php
    function validatePassword($pass){
        if (validateStringNotLess($pass, 8)){
            return true;           
        }
        return false;
    }
    function validateLogin($login){
        if (validateStringNotLess($login, 3)){
            return true;           
        }
        return false;
    }
  

?>