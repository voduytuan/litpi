<?php
class SendMail
{
	protected $registry;
	public $fromName = '';
	public $fromEmail = '';
	public $toName = '';
	public $toEmail = '';
	public $mailSubject = '';
	public $mailContents = '';
	public $usingSMTP = true;
	public $mailer;
	public $toArray = null;	// co dang array('email' => 'fullname', 'email' => 'fullname')
	
	function __construct($registry, $toEmail = '', $toName = '', $mailSubject = '', $mailContents = '', $fromEmail = '', $fromName = '')
	{
		$this->registry = $registry;
		$this->fromEmail = $fromEmail;
		$this->fromName = $fromName;
		$this->mailSubject = $mailSubject;
		$this->mailContents = $mailContents;
		$this->toEmail = $toEmail;
		$this->toName = $toName;
		
	}
	
	public function Send()
	{
		//$this->mailer->SMTPDebug;
		//return $this->preview();
		//return false;
		
		//su dung co che goi mail thuong
		//neu enable smtp thi su dung smtp
		//ko thi su dung mail thuong
		$this->usingSMTP = $this->registry->conf['smtp']['enable'];
		$this->mailer = new PHPMailer();
		
		$this->mailer->From = $this->fromEmail;
		$this->mailer->FromName = $this->fromName;
		$this->mailer->MsgHTML($this->mailContents);
		$this->mailer->Subject = $this->mailSubject;
		
		if($this->toEmail != '')
		{
			$this->mailer->AddAddress($this->toEmail, $this->toName);
		}
		
		if(is_array($this->toArray))
		{
			foreach($this->toArray as $email => $fullname)
			{
				$this->mailer->AddAddress($email, $fullname);
			}
		}
		
		$this->mailer->Port = 465;
		$this->mailer->AddReplyTo($this->fromEmail, $this->fromName);
		$this->mailer->CharSet="utf-8";	//fix bug unicode in email sender, subject, text
		
		if($this->usingSMTP)
		{
			$this->mailer->IsSMTP();
			$this->mailer->SMTPAuth = true;
			$this->mailer->SMTPSecure = 'ssl';
			
			if(is_array($this->registry->conf['smtp']['host']) && is_array($this->registry->conf['smtp']['username']) && is_array($this->registry->conf['smtp']['password']))
			{
				$robinCount = count($this->registry->conf['smtp']['host']);
				
				//get random number to chose random host/username/pass group
				$randomIndex = rand(0, $robinCount-1);
				
				$this->mailer->Host = $this->registry->conf['smtp']['host'][$randomIndex];	
				$this->mailer->Username = $this->registry->conf['smtp']['username'][$randomIndex];	
				$this->mailer->Password = $this->registry->conf['smtp']['password'][$randomIndex];	
			}
			else
			{
				//work with old site config
				$this->mailer->Host = $this->registry->conf['smtp']['host'];         
				$this->mailer->Username = $this->registry->conf['smtp']['username'];
				$this->mailer->Password = $this->registry->conf['smtp']['password'];	
			}
		}
		$result = $this->mailer->Send();
		
		
		
		
		return $result;		
	}
	
	private function preview()
	{
		echo '<div style="background:#fff;margin:20px;padding:10px;line-height:1.5;">
			<div>
				<div style="font-weight:bold;font-size:18px;">'.$this->mailSubject.'</div>
				<div><span style="color:#999;">From:</span> '.$this->fromName.' <span style="color:#999;">&lt;'.$this->fromEmail.'&gt;</span></div>
				<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999;">To:</span> '.$this->toName.' <span style="color:#999;">&lt;'.$this->toEmail.'&gt;</span></div>
				<div style="border-bottom:3px dotted #ddd;margin-bottom:20px;">&nbsp;</div>
			</div>
			<div>'.$this->mailContents.'</div>
		
		</div>';
		
		return true;
	}
}
?>
