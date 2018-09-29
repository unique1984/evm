<?php
#---------------------------------------------------------------------#
# evm                                                                 #
#                                                                     #
# easy virtualbox management php parser script       				  #
#                                                                     #
# Script	: evm_parser.php                                          #
# Version	: 1.0.0                                                   #
# Author	: Yasin KARABULAK <yasinkarabulak@gmail.com>              #
# Date		: 2017-08-30                                              #
#---------------------------------------------------------------------#
if(!isset($argv) || !$argv[1]){
	die("Scripti evm konsol aracı ile kullanınız.\n");
}
$arguments=evm::arguments($argv[1]);
$islem=$arguments[0];
$clear=isset($arguments[1])&&$arguments[1]=="clear"?true:false;
$machines=array();
if(count($argv>2)){
	for($i=2;$i<count($argv);$i++){
		$machines[]=$argv[$i];
	}
}
class evm {
	public static function all_machines(){
		exec("vboxmanage list vms | awk '{print $1}' | sort",$all_machines);
		return self::clear($all_machines);
	}
	
	public static function running_machines($paused=false){
		exec("vboxmanage list runningvms | awk '{print $1}' | sort",$running_machines);
		$running_machines=self::clear($running_machines);
	
		$paused_return=array();
		foreach($running_machines as $machine){
			exec("vboxmanage showvminfo '".$machine."' | grep State: | awk '{print $2}' | sort",$paused_machines);
			//~ $paused[$machine]=$paused_machines[0];
			if(trim($paused_machines[0])=="paused"){
				$paused_return[]=$machine;
			}
			$paused_machines=array();
		}
	
		if(!$paused){
			return array_diff($running_machines,$paused_return);
		}else{
			return $paused_return;
		}
	}
	
	public static function paused_machines(){
		return self::running_machines(true);
	}
	
	public static function stopped_machines(){
		return array_diff(self::all_machines(),self::running_machines(),self::paused_machines());
	}
	
	public static function clear(array $data){
		$return=array();
		foreach($data as $d){
			$return[]=str_replace('"',"",$d);
		}
		return $return;
	}
	
	public static function status(){
		self::running();
		self::paused();
		self::stopped();
	}
	
	public static function running($clear=null){
		$return=array();
		foreach(self::running_machines() as $running){
			if(!is_null($clear)){
				$return[] = $running;
			}else{
				//~ $cpu=cpu_usage();
				$return[] = "\e[42m\e[107m\e[1mAçık   :\e[0m ".$running;
			}
		}
	
		if(!is_null($clear) && count($return)>0){
			echo implode(" ",$return)." all";
		}elseif(is_null($clear) && count($return)>0){
			echo implode("\n",$return)."\n";
		}elseif(is_null($clear) && count($return)==0){
			echo "\e[31m\e[107m\e[1mAktif Çalışan sanal makine bulunamadı.\e[0m\n";
		}
	}
	
	public static function paused($clear=null){
		$return=array();
		foreach(self::paused_machines() as $paused){
			if(!is_null($clear)){
				$return[] = $paused;
			}else{
				$return[] = "\e[100m\e[1mPaused :\e[0m ".$paused;
			}
		}
	
		if(!is_null($clear) && count($return)>0){
			echo implode(" ",$return)." all";
		}elseif(is_null($clear) && count($return)>0){
			echo implode("\n",$return)."\n";
		}elseif(is_null($clear) && count($return)==0){
			echo "\n\e[31m\e[107m\e[1mDuraklatılmış sanal makine bulunamadı.\e[0m\n\n";
		}
	}
	
	public static function stopped($clear=null){
		$return=array();
		foreach(self::stopped_machines() as $stopped){
			if(!is_null($clear)){
				$return[] = $stopped;
			}else{
				$return[] = "\e[31m\e[107m\e[1mKapalı :\e[0m ".$stopped;
			}
		}
	
		if(!is_null($clear) && count($return)>0){
			echo implode(" ",$return)." all";
		}elseif(is_null($clear) && count($return)>0){
			echo implode("\n",$return)."\n";
		}elseif(is_null($clear) && count($return)==0){
			echo "\e[31m\e[107m\e[1mKapalı sanal makine bulunamadı.\e[0m\n\n";
		}
	}
	
	public static function start_machine($machine){
		echo "\e[40m'".$machine."'\e[0m";
		if(self::is_running($machine)){
			echo "\e[31m\e[107m\e[1mSanal makine ".$calisan."\e[31m\e[107m\e[1m zaten çalışıyor !\e[0m\n\n";
			echo "Durdurmak için \n\e[42m\e[40m\e[1mevm stop $machine\e[0m\n\n";
		}elseif(self::is_stopped($machine)){
			$acik=0;
			$komut=0;
			while($acik<1){
				//~ running();
				echo ".";
				if(self::is_running($machine)){
					$acik=1;
					echo "açıldı.\n\n";
				}
				if($komut==0){
					shell_exec('vboxmanage startvm "'.$machine.'" --type headless 2>&1 &');
					$komut=1;
				}
				//~ sleep(1);
			}
		}else{
			echo "\e[31m\e[107m\e[1mSanal makine ".$machine."\e[31m\e[107m\e[1m isminde bir sanal makine bulunamadı !\e[0m\n\n";
			echo "\e[40mAşağıda çalıştırabileceğiniz sanal makineler listelenmiştir.\e[0m\n";
			self::stopped();
		}
	}
	
	public static function pause_machine($machine){
		echo "\e[40m'".$machine."'\e[0m";
		if(in_array($machine,self::paused_machines())){
			echo "\e[31m\e[107m\e[1mSanal makine ".$machine."\e[31m\e[107m\e[1m zaten beklemede !\e[0m\n\n";
			echo "Çalışır duruma dönmek için \n\e[42m\e[40m\e[1mevm resume $machine\e[0m\n\n";
		}elseif(in_array($machine,self::running_machines())){
			$acik=0;
			$komut=0;
			while($acik<1){
				//~ running();
				echo ".";
				if(in_array($machine,self::paused_machines())){
					$acik=1;
					echo "beklemeye alındı.\n\n";
				}
				if($komut==0){
					shell_exec('vboxmanage controlvm "'.$machine.'" pause  2>&1 &');
					$komut=1;
				}
				//~ sleep(1);
			}
		}else{
			echo "\e[31m\e[107m\e[1mÇalışan sanal makine ".$machine."\e[31m\e[107m\e[1m isminde bir sanal makine bulunamadı !\e[0m\n\n";
			echo "\e[40mAşağıda beklemeye alınabilecek sanal makineler listelenmiştir.\e[0m\n";
			self::running();
		}
	}
	
	public static function resume_machine($machine){
		echo "\e[40m'".$machine."'\e[0m";
		if(in_array($machine,self::running_machines())){
			echo "\e[31m\e[107m\e[1mSanal makine ".$machine."\e[31m\e[107m\e[1m zaten çalışıyor !\e[0m\n\n";
			echo "Beklemeye almak için \n\e[42m\e[40m\e[1mevm pause $machine\e[0m\n\n";
		}elseif(in_array($machine,self::paused_machines())){
			$return= $machine;
			//~makine çalıştı mesajı
			$acik=0;
			$komut=0;
			while($acik<1){
				//~ running();
				echo ".";
				if(in_array($machine,self::running_machines())){
					$acik=1;
					echo "beklemeden çıkartıldı.\n\n";
				}
				if($komut==0){
					shell_exec('vboxmanage controlvm "'.$machine.'" resume  2>&1 &');
					$komut=1;
				}
				//~ sleep(1);
			}
		}else{
			echo "\e[31m\e[107m\e[1mBeklemede sanal makine ".$machine."\e[31m\e[107m\e[1m isminde bir sanal makine bulunamadı !\e[0m\n\n";
			echo "\e[40mAşağıda beklemedeki sanal makineler listelenmiştir.\e[0m\n";
			self::paused();
		}
	}
	
	public static function stop_machine($machine){
		echo "\e[40m'".$machine."'\e[0m";
		if(in_array($machine,self::stopped_machines())){
			echo "\e[31m\e[107m\e[1mSanal makine ".$machine."\e[31m\e[107m\e[1m zaten kapalı !\e[0m\n\n";
			echo "Çalıştırmak için \n\e[42m\e[40m\e[1mevm start $machine\e[0m\n\n";
		}elseif(in_array($machine,self::running_machines())){
			$acik=1;
			$komut=0;
			while($acik>0){
				//~ stopped();
				echo ".";
				if(in_array($machine,self::stopped_machines())){
					$acik=0;
					echo "kapandı.\n\n";
				}
				if($komut==0){
					shell_exec('vboxmanage controlvm "'.$machine.'" acpipowerbutton &');
					$komut=1;
				}
				//~ sleep(1);
			}
		}else{
			echo "\e[31m\e[107m\e[1mSanal makine ".$machine."\e[31m\e[107m\e[1m isminde bir sanal makine bulunamadı !\e[0m\n\n";
			echo "\e[40mAşağıda durdurabileceğiniz sanal makineler listelenmiştir.\e[0m\n";
			self::running();
		}
	}
	public static function show_machine($machine){
		if(self::is_running($machine)){
			shell_exec('vboxmanage startvm "'.$machine.'" --type separate > /dev/null 2>&1');
			echo "\e[42m\e[44m\e[1mGUI aktif   :\e[0m ". $machine ."\n";
		}
	}
	public static function start_all(){
		echo "Bellek miktarı yüzünden test edilmedi..";
		//~ foreach(stopped_machines() as $machine){
			//~ echo start_machine($machine);
		//~ }
		//~ status();
	}
	
	public static function stop_all(){
		foreach(self::running_machines() as $machine){
			echo self::stop_machine($machine);
		}
		self::status();
	}
	
	public static function pause_all(){
		foreach(self::running_machines() as $machine){
			echo self::pause_machine($machine);
		}
		self::status();
	}
	
	public static function resume_all(){
		foreach(self::paused_machines() as $machine){
			echo self::resume_machine($machine);
		}
		self::status();
	}
	
	public static function is_machine($machine){
		return in_array($machine,self::all_machines())?
			true:
			false;
	}
	
	public static function is_paused($machine){
		return self::is_machine($machine) && in_array($machine,self::paused_machines())?
			true:
			false;
	}
	
	public static function is_running($machine){
		return self::is_machine($machine) && in_array($machine,self::running_machines())?
			true:
			false;
	}
	
	public static function is_stopped($machine){
		return self::is_machine($machine) && in_array($machine,self::stopped_machines())?
			true:
			false;
	}
	public static function clone_vm($machine){
		//~ duran makinalar listelenecek
		print_r(self::all_machines());
	}
	public static function arguments($arg){
		return explode(" ",trim($arg));
	}
}
//~ class ends.
if($islem=="status"){
	evm::status();
}
if($islem=="get_stopped"){
	evm::stopped($clear?1:null);
}
if($islem=="get_running"){
	evm::running($clear?1:null);
}
if($islem=="get_paused"){
	evm::paused($clear?1:null);
}
if($islem=="start_machine"){
	foreach($machines as $machine){
		evm::start_machine($machine);
	}
	evm::running();
}
if($islem=="start_all"){
	evm::start_all();
}
if($islem=="stop_machine"){
	foreach($machines as $machine){
		evm::stop_machine($machine);
	}
	evm::stopped();
}
if($islem=="show_machine"){
	foreach($machines as $machine){
		evm::show_machine($machine);
	}
	evm::running();
}
if($islem=="stop_all"){
	evm::stop_all();
}
if($islem=="pause_machine"){
	foreach($machines as $machine){
		evm::pause_machine($machine);
	}
	evm::paused();
}
if($islem=="pause_all"){
	evm::pause_all();
}
if($islem=="resume_machine"){
	foreach($machines as $machine){
		evm::resume_machine($machine);
	}
	evm::running();
}
if($islem=="resume_all"){
	evm::resume_all();
}
if($islem=="clone_machine"){
	evm::clone_vm($machines[0]);
}
