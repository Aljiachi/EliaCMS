<?php

class Paginator{
    private $CurrentPage,$PagesNumbers,$PerPage,$Number,$Get,$page,$Lanugages,$Stlyes;
    
    public function __construct(){
        $this->PerPage = 3;
        $this->Get = 'limit';
        if(!preg_match('/\?/',$_SERVER['PHP_SELF']))
            $this->page = $_SERVER['PHP_SELF'].'?';
        else 
            $this->page = $_SERVER['PHP_SELF'].'&';
        $this->Lanugages = array(
                        'next' => 'التالي',
                        'prev' => 'السابق',
            'page' => 'الصفحة',
            'from' => 'من'
                    );
        $this->Styles = array(
            'current' => 'current',
                        'disabled' => 'disabled',
            'paginator-msg' => 'pagmsg',
            'paginator' => 'pagination',
			'enabled' => 'page-link' ,
            );
    }
    
    public function SetPerPage($PerPage){
        $this->PerPage = intval(abs(ceil($PerPage)));
    }
    
    public function SetGetPage($get){
        $this->Get = $get;
    }
    
    public function SetPageUrl($url){
        if(!preg_match('/\?/',$url))
            $url = $url.'?';
        else 
            $url = $url.'&';
        $this->page = $url;
    }
    
    
    
    public function ShowPagesList(){
    $select = '<form method="get" action="'.$this->page.'">
        <span class="'.$this->Stlyes['paginator-msg'].'">
            '.$this->Lanugages['page'].' '.$this->CurrentPage.' '.$this->Lanugages['from'].' '.$this->Number.'
        </span>
    <select onchange="window.location=this.options[this.selectedIndex].value;">';
    for($i=1;$i<=$this->PagesNumbers;$i++){
            if($i == $this->CurrentPage){
                $select .= '<option value="'.$this->page.$this->Get.'='.$i.'" selected="selected">'.$i.'</option>'."\n";
            }else{
                $select .= '<option value="'.$this->page.$this->Get.'='.$i.'">'.$i.'</option>'."\n";
            }
        }
    $select .= "</select>
            </form>";
    return $select;
    }
    
    /*
    * Create Pagination
    * @param : Query
    * @access : public
    * @return : array with result of Query from database
    */
    public function CreatePaginator($Query){
         $this->Number = $this->CountPages($Query); // count pages
        $this->PagesNumbers = ceil($this->Number/$this->PerPage);// get page numbers
        // checking get page
        if(isset($_GET[$this->Get]) && $_GET[$this->Get] > 0 && $_GET[$this->Get] <= $this->PagesNumbers && is_numeric($_GET[$this->Get])){
        $this->CurrentPage = intval(abs($_GET[$this->Get]));// set the current page
        }else{
            $this->CurrentPage = 1;// set the current page as the first
        }
        // add limit to the query
        $newQuery = mysql_query($Query." LIMIT ".(($this->CurrentPage-1) * $this->PerPage)." , ".$this->PerPage);
		
		$this->TotalRows = mysql_num_rows(mysql_query($Query));
        // start the while and set the result in athor array
		return $newQuery;
    }
    
    /*
    * Count The Page Namber
    * @param: Query
    * @return : Number of Pages
    * @access : priviate
    */
    private function CountPages($Query){
        $count = mysql_query($Query); // query
        $Number = mysql_num_rows($count);// num
        mysql_free_result($count);// free
        return $Number;// return the number
    }
    
    /*
    * Get The Pagination
    * @return : The Pagination
    * @access : public
    */
    public function GetPagination(){
        $next = $this->CurrentPage + 1; // get next page number
        $prev = $this->CurrentPage - 1; // get prev page number
        $pagination = '<div class="'.$this->Styles['paginator'].'">'."\n"; // start the pagination
        // get the next page
        if($this->CurrentPage > 1 && $this->CurrentPage <= $this->PagesNumbers){
            $pagination .= '<span><a href="'.$this->page.$this->Get.'='.$prev.'">'.$this->Lanugages['prev'].'</a></span>'."\n";
        }else{
            $pagination .= '<span class="'.$this->Styles['disabled'].'">'.$this->Lanugages['prev'].'</span>'."\n";
        }
    // get the pagination numbers
        for($i=1;$i<=$this->PagesNumbers;$i++){
            if($i == $this->CurrentPage){
                $pagination .= '<span class="'.$this->Styles['current'].'">'.$i.'</span>'."\n";
            }else{
                $pagination .= '<a href="'.$this->page.$this->Get.'='.$i.'">'.$i.'</a>'."\n";
            }
        }
    // get prev 

		    if($this->CurrentPage < $this->PagesNumbers){
            $pagination .= '<span><a href="'.$this->page.$this->Get.'='.$next.'">'.$this->Lanugages['next'].'</a></span>'."\n";
        }  else{
            $pagination .= '<span class="'.$this->Styles['disabled'].'">'.$this->Lanugages['next'].'</span>'."\n";
        }
    $pagination .= '</div>'."\n";
        return $pagination;
    }
}
?>