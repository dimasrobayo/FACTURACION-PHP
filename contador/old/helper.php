<?php
/**
* @version		$Id: helper.php 2007-10-17 vinaora $
* @package		Joomla
* @copyright		Copyright (C) 2006 - 2008 VINAORA.COM. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
//defined('_JEXEC') or die('Restricted access');

class modVisitCounterHelper
{
	function render(&$params)
	{
		// Read our Parameters
	/*	
		$today			=	@$params->get('today', 'Today');
		$yesterday		=	@$params->get('yesterday', 'Yesterday');
		$x_month		=	@$params->get('month', 'This month');
		$x_week			=	@$params->get('week', 'This week');
		$all			=	@$params->get('all', 'All days');
		
		$locktime		=	@$params->get('locktime', 60);
		$initialvalue	=	@$params->get('initialvalue', 1);
		$records		=	@$params->get('records', 1);
		
		$s_today		=	@$params->get('s_today', 1);
		$s_yesterday	=	@$params->get('s_yesterday', 1);
		$s_all			=	@$params->get('s_all', 1);
		$s_week			=	@$params->get('s_week', 1);
		$s_month		=	@$params->get('s_month', 1);
		
		$s_digit		=	@$params->get( 's_digit', 1 );
		$disp_type 		= 	@$params->get( 'disp_type', "a" );
		
		$widthtable		=	@$params->get( 'widthtable', "100" );
		$pretext  		= 	@$params->get( 'pretext', "" );
		$posttext  		= 	@$params->get( 'posttext', "" );
		*/
		$today			=	'Hoy';
		$yesterday		=	'Ayer';
		$x_month		=	'Este mes';
		$x_week			=	'Esta semana';
		$all			=	'Total de Visitas';
		
		$locktime		=	60;
		$initialvalue	=	0;
		$records		=	500000;
		
		$s_today		=	1;
		$s_yesterday	=	1;
		$s_all			=	1;
		$s_week			=	1;
		$s_month		=	1;
		
		$s_digit		=	1;
		$disp_type 		= 	"mechanical";
		
		$widthtable		=	"100";
		$pretext  		= 	"";
		$posttext  		= 	"";
		// From minutes to seconds
		$locktime		=	$locktime * 60;
		
		
		$link=mysql_connect('localhost','root','jireh');
		mysql_select_db('biblioteca-post');
		// Check if table exists. When not, create it
		$query	   =	"CREATE TABLE IF NOT EXISTS vvisitcounter (id int(11) unsigned NOT NULL auto_increment, tm int not null, ip varchar(16) not null default '0.0.0.0', PRIMARY KEY (id)) ENGINE=MyISAM AUTO_INCREMENT=1";
		$resultado = mysql_query($query);
	
		
		// Now we are checking if the ip was logged in the database. Depending of the value in minutes in the locktime variable.
		$day			 =	date('d');
		$month			 =	date('n');
		$year			 =	date('Y');
		$daystart		 =	mktime(0,0,0,$month,$day,$year);
		$monthstart		 =  mktime(0,0,0,$month,1,$year);
		
		// weekstart
		$weekday		 =	date('w');
		$weekday--;
		if ($weekday < 0)$weekday = 7;
		$weekday		 =	$weekday * 24*60*60;
		$weekstart		 =	$daystart - $weekday;

		$yesterdaystart	 =	$daystart - (24*60*60);
		$now			 =	time();
		$ip				 =	$_SERVER['REMOTE_ADDR'];
		
		$query			 =	"SELECT MAX(id) FROM vvisitcounter";
		$resultado		 =	mysql_query($query);
		$temporal	     =	mysql_fetch_row($resultado);
		$all_visitors	 =	$temporal[0];
		mysql_free_result($resultado);
		
		if ($all_visitors == NULL) {
			$all_visitors = $initialvalue;
		} else {
			$all_visitors += $initialvalue;
		}
		
		// Delete old records
		$temp=$all_visitors-$records;
		
		if ($records>0){
			$query		 =  "DELETE FROM vvisitcounter WHERE id<'$temp'";
            $resultado   =  mysql_query($query);
		}
		
		$query			 =	"SELECT COUNT(*) FROM vvisitcounter WHERE ip='$ip' AND (tm+'$locktime')>'$now'";
        $resultado		 =	mysql_query($query);
		$temporal	     =	mysql_fetch_row($resultado);							
		$items			 =	$temporal[0];
        mysql_free_result($resultado);
		
		if (empty($items))
		{
			$query = "INSERT INTO vvisitcounter (id, tm, ip) VALUES ('', '$now', '$ip')";
            $resultado			 =	mysql_query($query);
            $e = mysql_error();
		}
		
		$n				 = 	$all_visitors;
		$div = 100000;
		while ($n > $div) {
			$div *= 10;
		}

		$query			 =	"SELECT COUNT(*) FROM vvisitcounter WHERE tm>'$daystart'";
        $resultado		 =	mysql_query($query);
		$temporal	     =	mysql_fetch_row($resultado);							
		$today_visitors	 =	$temporal[0];		
        mysql_free_result($resultado);				
		
		$query			 =	"SELECT COUNT(*) FROM vvisitcounter WHERE tm>'$yesterdaystart' and tm<'$daystart'";
        $resultado		 =	mysql_query($query);
		$temporal	     =	mysql_fetch_row($resultado);							
		$yesterday_visitors	 =	$temporal[0];
		mysql_free_result($resultado);						
		
		$query			 =	"SELECT COUNT(*) FROM vvisitcounter WHERE tm>='$weekstart'";
		$resultado		 =	mysql_query($query);
		$temporal	     =	mysql_fetch_row($resultado);							
		$week_visitors	 =	$temporal[0];						
		mysql_free_result($resultado);
			
		$query			 =	"SELECT COUNT(*) FROM vvisitcounter WHERE tm>='$monthstart'";
        $resultado		 =	mysql_query($query);
		$temporal	     =	mysql_fetch_row($resultado);							
		$month_visitors	 =	$temporal[0];								
		mysql_free_result($resultado);

		$content = '<div>';
		if ($pretext != "") $content .= '<div>'.$pretext.'</div>';
		// Show digit counter
		if($s_digit){		
			$content .= '<div style="text-align: center;">';
			while ($div >= 1) {
				$digit = $n / $div % 10;
				$content .= '<img src="images/'.$disp_type.'/'.$digit.'.gif" alt="mod_vvisit_counter" />';
				$n -= $digit * $div;
				$div /= 10;
			}
			$content .= '</div>';
		}
		
		$content		 .=	'<div><table cellpadding="0" cellspacing="0" style="text-align: center; width: '.$widthtable.'%;" class="vinaora_counter"><tbody align="center">';
		// Show today, yestoday, week, month, all statistic
		if($s_today)		$content		.=	$this-> spaceer("vtoday.gif", $today, $today_visitors);
		if($s_yesterday)	$content		.=	$this-> spaceer("vyesterday.gif", $yesterday, $yesterday_visitors);
		if($s_week)			$content		.=	$this-> spaceer("vweek.gif", $x_week, $week_visitors);
		if($s_month)		$content		.=	$this-> spaceer("vmonth.gif", $x_month, $month_visitors);
		if($s_all)			$content		.=	$this-> spaceer("vall.gif", $all, $all_visitors);
		
		$content		.= "</tbody></table></div>";
		if ($posttext != "") $content		.= '<div>'.$posttext.'</div>';
		$content .= "</div>";
		echo $content;
	}
	function spaceer($a1,$a2,$a3)
	{
		$ret = '<tr align="center"><td><img src="images/'.$a1.'" alt="mod_vvisit_counter"/></td>';
		$ret .= '<td>'.$a2.'</td>';
		$ret .= '<td align="right">'.$a3.'</td></tr>';
		return $ret;
	}
}
