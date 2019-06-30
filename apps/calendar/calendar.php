<?php

/*
* Takvim API
* @author: Ilhami Tugral <ilhamitugral@gmail.com>
* @date: 21/05/19
* @copyright: Ilhami Tugral
*/

require_once(__DIR__.'/../../inc/init.php');

class Calendar {

    private $_lang;

    private function GetLanguageFile() {
        if(GetLanguageDir(USER_LANG)) {
            require(__DIR__.'/../../conf/lang/'.USER_LANG.'/main.php');
        }else {
            require(__DIR__.'/../../conf/lang/en/main.php');
        }
        return $lang;
    }

    private function GetCalendar($month, $year) {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates = '';
        $dayCounter = date("N", strtotime('1 '.date("F Y")));
    
        for($i = 1; $i < $dayCounter; $i++) {
            $dates .= '<td><a class="calendar-day empty" href="#">&nbsp;</a></td>';
        }
    
        for($i = 1; $i <= $daysInMonth; $i++) {
            if($i == date("d")) {
                $today = ' today';
            }else {
                $today = '';
            }
            $dayCounter++;
    
            $dates .= '
            <td>
                <a class="calendar-day'.$today.'" href="#">'.$i.'</a>
            </td>';
            if($dayCounter > 7 && $i !== $daysInMonth) {
                $dates .= '</tr><tr>';
                $dayCounter = 1;
            }
        }
    
        for($i = $dayCounter; $i <= 7; $i++) {
            $dates .= '<td><a class="calendar-day empty" href="#">&nbsp;</a></td>';
        }
    
        return $dates;
    }

    private function GetCalendarDays() {
        $_lang = $this->GetLanguageFile();
        $days = '';
        for($i = 1; $i <= 7; $i++) {
            $days .= '<th><a href="#">'.$_lang["short_day_".$i].'</a></th>';
        }
        return $days;
    }

    public function ShowCalendar() {
        $_lang = $this->GetLanguageFile();
        ?>
        <section class="section" data-name="Calendar">
            <span class="title"><i class="fa fa-calendar-alt"></i>&nbsp;<?php echo $_lang["calendar"]; ?></span>
            <div class="calendar">
                <div class="calendar-today">
                    <p><?php echo date("d").' '.$_lang["month_".date("n")].' '.date("Y"); ?></p>
                </div>
                <table class="calendar-table" cellpadding="5" cellspacing="5">
                    <tr>
                        <?php echo $this->GetCalendarDays(); ?>
                    </tr>
                    <tr>
                        <?php echo $this->GetCalendar(date("m"), date("Y")); ?>
                    </tr>
                </table>
            </div>
        </section>
        <?php
    }

}

?>