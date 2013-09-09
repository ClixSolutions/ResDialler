<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
 class Xml extends Output
 {
 	 public function sendOutput()
 	 {
 	 	 if(!$this->result)
 	 	   return;
 	 	
 	 	 header("Content-Type: application/rss+xml; charset=ISO-8859-1");
		
		 $rssfeed  = '<?xml version="1.0" encoding="ISO-8859-1"?>';
		 $rssfeed .= '<rss version="2.0">';
			 $rssfeed .= '<channel>';
			   $rssfeed .= '<title>' . $this->result['xmlTitle'] . '</title>';
			   $rssfeed .= '<link>http://www.clixmedia.co.uk</link>';
			   $rssfeed .= '<description>' . $this->result['xmlDescription'] . '</description>';
			   $rssfeed .= '<language>en-gb</language>';
			   $rssfeed .= '<ttl>5</ttl>';
			   $rssfeed .= '<copyright>Copyright (C) ' . date("Y") . ' Clix Media</copyright>';
			   $rssfeed .= '<webMaster>rssfeeds@clixmedia.co.uk</webMaster>';
				
			   if(count($this->result['element']) > 0)
			   {
			 	   foreach($this->result['element'] as $value)
				   {
				     $rssfeed .= '<item>';
			         $rssfeed .= '<title>' . $value['title'] . '</title>';
			         $rssfeed .= '<description><![CDATA[' . $value['description'] . ']]></description>';
			         $rssfeed .= ($value['link'] ? '<link>' . $value['link'] . '</link>' : false);
			         $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O", strtotime($value['date'])) . '</pubDate>';
             $rssfeed .= '</item>';
				   }
			   }
				
			   $rssfeed .= '</channel>';
	     $rssfeed .= '</rss>';
	    
	   return $rssfeed;
 	 }
 }
?>