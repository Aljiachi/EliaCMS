<?php
/**
 * This program is under GNU GPL license.
 *
 * You can contact the author of this program at <ffmandu13@hotmail.com/>.
 */

//Defined regexps (you can add your own ones).
define('REG_DATE'          , '([[:digit:]]{4})-([[:digit:]]{2})-([[:digit:]]{2})');
define('REG_DIGIT_SIGNED'  , '^[-[:digit:]]+$');
define('REG_DIGIT_UNSIGNED', '^[[:digit:]]+$');
define('REG_PASSWORD'      , '^[[:alnum:]]+$');
define('REG_TEXT'          , '[[:graph:][:blank:]]+');
define('REG_WORD'          , '^[[:alpha:]]+$');

//Controls contents of the $_REQUEST variable.
final class checkVar{

  private $tmp; //Secured value of a $_REQUEST key.

  //Check if the variable is set.
  private function isSet(&$field){
    if(!isset($_REQUEST[$field]))
      throw new Exception("You forgot to fill the $field field.");
    else
      return true;
  }

  //Set $tmp and remove threatening characters.
  private function removeCharsThreats(&$field){
    $this->tmp = trim($_REQUEST[$field]);
    $this->tmp = htmlspecialchars($_REQUEST[$field], ENT_QUOTES, 'UTF-8', false); 
  }

  //Checks if the value is equal to 1.
  public function securityBool($field){
    if($this->isSet($field) && $_REQUEST[$field] != 1)
      throw new Exception("Unallowed value in $field field.");
    else
      return true;
  }

  //Checks if the value is in the allowed ones list ($enum).
  public function securityEnum($field, $enum){
    if($this->isSet($field)){
      $this->removeCharsThreats($field);
      $tab = explode(',', $enum);
      if(!in_array($this->tmp, $tab))
        throw new Exception("Unallowed value in $field field.");
      else
        return (string) $this->tmp;
    }
  }

  //Checks if the value is a numeric one and if it is in the given range.
  public function securityRange($field, $range){
    if($this->isSet($field)){
      $this->removeCharsThreats($field);
      $tab = explode('/', $range);
      if(!is_numeric($this->tmp))
        throw new Exception("Unallowed characters in $field field.");
      elseif($this->tmp < $tab[0] || $this->tmp > $tab[1])
        throw new Exception('Value must be in range '.$tab[0].'/'.$tab[1]." in $field field.");
      else
        return (int) $this->tmp;
    }
  }

  /**
   * Checks if the value respects the defined regexp,
   * and if its length is not superior than the given maxlength.
   */
  public function securityText($field, $maxlength, $regexp){
    if($this->isSet($field)){
      $this->removeCharsThreats($field);
      if(!mb_ereg($regexp, $this->tmp))
        throw new Exception("Unallowed characters in $field field.");
      elseif(mb_strlen($this->tmp, ENCODING) > $maxlength)
        throw new Exception("Too long string length for $field field.");
      else
        return $this->tmp;
    }
  }

}
?>