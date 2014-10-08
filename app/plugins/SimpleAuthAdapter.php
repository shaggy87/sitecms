<?php
/**
 * Simple Auth Adapter
 */

class Application_Plugin_SimpleAuthAdapter implements Zend_Auth_Adapter_Interface
{
	private $uname;
	private $pwd;
	
	private $uid;
	private $sessid;
	private $username;
	
	private $accounts = array(
		
		'0' => array(
			'username' => 'admin',
			'full_name' => 'The Admin',
			'password' => 'ZubizaReta',
			'role' => 'superuser',
            'partner' => 'hive'
            ),
		
		
		'1' => array(
			'username' => 'videomanager',
			'full_name' => 'VideoManager',
			'password' => 'LeroyLita',
			'role' => 'videomanageruser',
            ),
		
		// Instadeal
		'3' => array(
            'username' => 'instadeal',
            'skin' => 'InstaDeal',
            'password' => 'ZlatanIbrahimovic#10',
            'role' => 'motdpromouser',
            'partner' => 'instadeal'
            ),	
            
        '4' => array(
            'username' => 'terminalpoker',
            'skin' => 'TerminalPoker',
            'password' => 'HenrikLarsson#7',
            'role' => 'motdpromouser',
            'partner' => 'terminalpoker'
            ),
            
        '5' => array(
            'username' => 'cardcasino',
            'skin' => 'CardCasino',
            'password' => 'FredrikLjungberg#8',
            'role' => 'motdpromouser',
            'partner' => 'cardcasino'
            ),  
        
        '6' => array(
            'username' => 'irisheyes',
            'skin' => 'IrishEyes',
            'password' => 'OlofMellberg#4',
            'role' => 'motdpromouser',
            'partner' => 'irisheyes'
            ),  
        
        '7' => array(
            'username' => 'odeonpoker',
            'skin' => 'OdeonPoker',
            'password' => 'KimKallstrom#9',
            'role' => 'motdpromouser',
            'partner' => 'odeonpoker'
            ),  
         
         '8' => array(
            'username' => 'buzzpoker',
            'skin' => 'BuzzPoker',
            'password' => 'MarkusRosenberg#22',
            'role' => 'motdpromouser',
            'partner' => 'buzzpoker'
            ),  
         
         '9' => array(
            'username' => 'follwrpoker',
            'skin' => 'FollwrPoker',
            'password' => 'AndreasIsaksson#1',
            'role' => 'motdpromouser',
            'partner' => 'follwrpoker'
            ),
            
		  '10' => array(
            'username' => 'everwin',
            'skin' => 'EverWin',
            'password' => 'JohanElmander#11',
            'role' => 'motdpromouser',
            'partner' => 'everwin'
           ),
            
		  '11' => array(
            'username' => 'everlast',
            'skin' => 'Everlast',
            'password' => 'OlaToivonen#20',
            'role' => 'motdpromouser',
            'partner' => 'everlast'
           ),
           
		   
		  // PlanetWin365
		  '12' => array(
            'username' => 'planetwin',
            'skin' => 'PlanetWin',
            'password' => 'admin',
            'role' => 'motdpromouser',
            'partner' => 'PlanetWin365',
            'skinid' => 'planetwin'
           ),
           
           '13' => array(
            'username' => 'jokerbet',
            'skin' => 'JokerBet',
            'password' => 'Cavenaghi#19',
            'role' => 'motdpromouser',
            'partner' => 'jokerbet'
           ),
           
		   
		   // VEVO
		   '14' => array(
            'username' => 'vevopoker',
            'skin' => 'VevoPoker',
            'password' => 'JavierMascherano#4',
            'role' => 'motdpromouser',
            'partner' => 'vevo'
           ),
           
		   '15' => array(
            'username' => 'arabiangaming',
            'skin' => 'ArabianGaming',
            'password' => 'CarlosTevez#10',
            'role' => 'motdpromouser',
            'partner' => 'vevo'
           ),
           
		   '16' => array(
            'username' => 'imajbet',
            'skin' => 'ImajBet',
            'password' => 'GonzaloHiguain#9',
            'role' => 'motdpromouser',
            'partner' => 'vevo'
           ),
           
		   // Yahting
		   '17' => array(
            'username' => 'yahting',
            'skin' => 'Yahting',
            'password' => 'MartinDemichelis#26',
            'role' => 'motdpromouser',
            'partner' => 'Yahting'
           ),
           
		   // 3Bet Gaming
		   '18' => array(
            'username' => '3bg',
            'skin' => '3BG',
            'password' => 'ClaudioCaniggia#11', 
            'role' => 'motdpromouser',
            'partner' => '3Bet Gaming'
           ),
           
		   '19' => array(
            'username' => 'bet1128',
            'skin' => 'Bet1128',
            'password' => 'DiegoMaradona#10',
            'role' => 'motdpromouser',
            'partner' => '3Bet Gaming'
           ),
	);

	
	/**
	 * Sets username and password for authentication
	 *
	 * @return void
	 */
	public function __construct($username = false, $password = false)
	{
		$this->uname = $username;
		$this->pwd = $password;
	}	 
	
	/**
	 * Performs an authentication attempt
	 * 
	 * @return Zend_Auth_Result
	 */
	public function authenticate()
	{
		$userdata = new stdClass();
		
		$authAdapter = $this->getAuthAdapter();

        $authAdapter->setIdentity($this->uname)
            ->setCredential(md5($this->pwd));

        $auth = Zend_Auth::getInstance();

        $authStorage = $auth->getStorage();
        $result = $auth->authenticate($authAdapter);

		if ($result->isValid()) {
			$Users = new Application_Model_HiveCmsUsers();
			
			$data = $Users->getUser($this->uname);

			$userdata = (object) $data;
			return new Zend_Auth_Result(1, $userdata);
		}
		return new Zend_Auth_Result(0, null);
	}
	
	/**
     *
     * Login auth adapter: Database driven
     */
    protected function getAuthAdapter()
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('hive_cms_users')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password');

        return $authAdapter;
    }
	

}