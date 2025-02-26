<?php

class Boleto {function Fc854e6f2(&$V0842f867) {
$Vb1e98914 = "237";$V05f04fce = "9";$V6365aec3 = "0";
$V33f2c330 = $this->F91da64a7($V0842f867["data_vencimento"]);$V01773a8a = $this->Fe70c1e8e($V0842f867["valor"],10,"0","v");
$V9f808afd = $this->Fe70c1e8e($V0842f867["agencia"],4,"0");$V0842f867["agencia"] = $V9f808afd;$Vef0ad7ba = $this->Fe70c1e8e($V0842f867["conta"],7,"0");$V0842f867["conta"] = $Vef0ad7ba ;
$V5b3b7abe = $this->Fe70c1e8e($V0842f867["nosso_numero"],11,"0");
$V7c3c1e38 = $V0842f867["carteira"];
$Vcbc3e21f = $this->F7ffb8a7a("$V7c3c1e38$V5b3b7abe",7);$Vefba5f56 = "$Vb1e98914$V05f04fce$V33f2c330$V01773a8a$V9f808afd$V7c3c1e38$V5b3b7abe$Vef0ad7ba$V6365aec3";
$Vdf561e09 = $this->Ff7e8dda1($Vefba5f56);
$Vefba5f56 = "$Vb1e98914$V05f04fce$Vdf561e09$V33f2c330$V01773a8a$V9f808afd$V7c3c1e38$V5b3b7abe$Vef0ad7ba$V6365aec3";
$V9c19074d = $V9f808afd ."/". $Vef0ad7ba . "-" . $V0842f867["digito_conta"];$V5b3b7abe = $V7c3c1e38 ."/". $V5b3b7abe ."-". $Vcbc3e21f;$V0842f867["codigo_barras"] = "$Vefba5f56";$V0842f867["linha_digitavel"] = $this->F31fc88ce($Vefba5f56);$V0842f867["agencia_codigo"] = $V9c19074d ;$V0842f867["nosso_numero"] = $V5b3b7abe;}
function Ff7e8dda1($V0842f867){$V0842f867 = $this->F7ffb8a7a($V0842f867);
if($V0842f867==0 || $V0842f867 >9)
$V0842f867 = 1;
return $V0842f867;}function F91da64a7($V0842f867){$V0842f867 = str_replace("/","-",$V0842f867);$V72d4501a = explode("-",$V0842f867);

return $this->Fa9c71202($V72d4501a[2], $V72d4501a[1], $V72d4501a[0]);}

function Fa9c71202($V3374bda8, $Vfa9ed7a4, $V72d4501a) {return(abs(($this->F0dd7d614("1997","10","07")) - ($this->F0dd7d614($V3374bda8, $Vfa9ed7a4, $V72d4501a))));
}function F0dd7d614($Vb55b1fdd,$Vba8af78c,$V4bc398e1) {$V9904bfb7 = substr($Vb55b1fdd, 0, 2);$Vb55b1fdd = substr($Vb55b1fdd, 2, 2);if ($Vba8af78c > 2) {$Vba8af78c -= 3;}else {$Vba8af78c += 9;if ($Vb55b1fdd) {$Vb55b1fdd--;}else {$Vb55b1fdd = 99;$V9904bfb7 --;}}
return ( floor((146097 * $V9904bfb7)/4 ) + floor(( 1461 * $Vb55b1fdd)/4 ) + floor(( 153 * $Vba8af78c +2) /5 ) + $V4bc398e1 +1721119);}

function F7ffb8a7a($Vbc918940, $Vfd7c5c4f=9,$V07450649=0) {$Ve934202c = 0;$V79d0d34d = 2;for ($V865c0c0b = strlen($Vbc918940); $V865c0c0b > 0; $V865c0c0b--) {$Vbcd4e9b3[$V865c0c0b] = substr($Vbc918940,$V865c0c0b-1,1);$V9cd69b0c[$V865c0c0b] = $Vbcd4e9b3[$V865c0c0b] * $V79d0d34d;$Ve934202c += $V9cd69b0c[$V865c0c0b];if ($V79d0d34d == $Vfd7c5c4f) {$V79d0d34d = 1;} $V79d0d34d++;}if ($V07450649 == 0) {$Ve934202c *= 10;$Va9624b72 =$Ve934202c % 11;
if ($Va9624b72 == 10) {$Va9624b72 = 0;}return $Va9624b72;}elseif ($V07450649 == 1){$Vb73d98bf =$Ve934202c % 11;return $Vb73d98bf;}}function F4e549e06($Vbc918940) {$V8cdfee6d = 0;$V79d0d34d = 2;for ($V865c0c0b = strlen($Vbc918940); $V865c0c0b > 0; $V865c0c0b--) {$Vbcd4e9b3[$V865c0c0b] = substr($Vbc918940,$V865c0c0b-1,1);$V4dfa4a52[$V865c0c0b] = $Vbcd4e9b3[$V865c0c0b] * $V79d0d34d;$V8cdfee6d .= $V4dfa4a52[$V865c0c0b];if ($V79d0d34d == 2) {$V79d0d34d = 1;}else {$V79d0d34d = 2;}}
$Ve934202c = 0;
for ($V865c0c0b = strlen($V8cdfee6d); $V865c0c0b > 0; $V865c0c0b--) {$Vbcd4e9b3[$V865c0c0b] = substr($V8cdfee6d,$V865c0c0b-1,1);$Ve934202c += $Vbcd4e9b3[$V865c0c0b];}$Vb73d98bf =$Ve934202c % 10;$Va9624b72 = 10 - $Vb73d98bf;if ($Vb73d98bf == 0) {$Va9624b72 = 0;}return $Va9624b72;}function F31fc88ce($V1d6a6687) {
$V4de8ac4a = substr($V1d6a6687, 0, 4);$Ve77e78f9 = substr($V1d6a6687, 19, 5);$V12452a6b = $this->F4e549e06("$V4de8ac4a$Ve77e78f9");$V63bd2143 = "$V4de8ac4a$Ve77e78f9$V12452a6b";$V223e4b9f = substr($V63bd2143, 0, 5);$V042d0a6f = substr($V63bd2143, 5);$Vfd76e92c = "$V223e4b9f.$V042d0a6f";
$V4de8ac4a = substr($V1d6a6687, 24, 10);$Ve77e78f9 = $this->F4e549e06($V4de8ac4a);$V12452a6b = "$V4de8ac4a$Ve77e78f9";$V63bd2143 = substr($V12452a6b, 0, 5);$V223e4b9f = substr($V12452a6b, 5);$V2e3c6c95 = "$V63bd2143.$V223e4b9f";
$V4de8ac4a = substr($V1d6a6687, 34, 10);$Ve77e78f9 = $this->F4e549e06($V4de8ac4a);$V12452a6b = "$V4de8ac4a$Ve77e78f9";$V63bd2143 = substr($V12452a6b, 0, 5);$V223e4b9f = substr($V12452a6b, 5);$Vda48ea4a = "$V63bd2143.$V223e4b9f";$V95f605a1 = substr($V1d6a6687, 4, 1);
$Vcec3bdda = substr($V1d6a6687, 5, 14);return "$Vfd76e92c $V2e3c6c95 $Vda48ea4a $V95f605a1 $Vcec3bdda";}function Fb658b2bd($V38741ea2) {$V8c0d3987 = substr($V38741ea2, 0, 3);$V902fbdd2 = $this->F7ffb8a7a($V8c0d3987);return $V8c0d3987 . "-" . $V902fbdd2;}function Fe70c1e8e($V0842f867, $V75b85826, $V903313b8, $V2681c044 = "e"){if($V2681c044=="v"){$V0842f867 = str_replace(".","",$V0842f867);$V0842f867 = str_replace(",",".",$V0842f867);$V0842f867 = number_format($V0842f867,2,"","");$V0842f867 = str_replace(".","",$V0842f867);$V2681c044 = "e";}while(strlen($V0842f867)<$V75b85826){if($V2681c044=="e"){$V0842f867 = $V903313b8 . $V0842f867;}else{$V0842f867 = $V0842f867 . $V903313b8;}}return $V0842f867;}}function Fe51a82ef($V01773a8a){$V77e77c6a = 1 ;$V5f44b105 = 3 ;$V2c9890f4 = 50 ;$Ve5200a9e[0] = "00110" ;$Ve5200a9e[1] = "10001" ;$Ve5200a9e[2] = "01001" ;$Ve5200a9e[3] = "11000" ;$Ve5200a9e[4] = "00101" ;$Ve5200a9e[5] = "10100" ;$Ve5200a9e[6] = "01100" ;$Ve5200a9e[7] = "00011" ;$Ve5200a9e[8] = "10010" ;$Ve5200a9e[9] = "01010" ;
for($Vbd19836d=9;$Vbd19836d>=0;$Vbd19836d--){
for($V3667f6a0=9;$V3667f6a0>=0;$V3667f6a0--){$V8fa14cdd = ($Vbd19836d * 10) + $V3667f6a0 ;$V62059a74 = "" ;
for($V865c0c0b=1;$V865c0c0b<6;$V865c0c0b++){$V62059a74 .= substr($Ve5200a9e[$Vbd19836d],($V865c0c0b-1),1) . substr($Ve5200a9e[$V3667f6a0],($V865c0c0b-1),1);}
$Ve5200a9e[$V8fa14cdd] = $V62059a74;
}
}

?>
<img src=<?php echo base_url(); ?>assets/plugins/boleto/p.gif width=<?php echo $V77e77c6a; ?> height=<?php echo $V2c9890f4; ?> border=0><img
src=<?php echo base_url(); ?>assets/plugins/boleto/b.gif width=<?php echo $V77e77c6a; ?> height=<?php echo $V2c9890f4; ?> border=0><img
src=<?php echo base_url(); ?>assets/plugins/boleto/p.gif width=<?php echo $V77e77c6a; ?> height=<?php echo $V2c9890f4; ?> border=0><img
src=<?php echo base_url(); ?>assets/plugins/boleto/b.gif width=<?php echo $V77e77c6a; ?> height=<?php echo $V2c9890f4; ?> border=0><img
<?php
$V62059a74 = $V01773a8a ;
if((strlen($V62059a74) % 2) <> 0){$V62059a74 = "0" . $V62059a74;}
while (strlen($V62059a74) > 0) {$V865c0c0b = round(Ff2317ae6($V62059a74,2));$V62059a74 = F0835e508($V62059a74,strlen($V62059a74)-2);$V8fa14cdd = $Ve5200a9e[$V865c0c0b];
for($V865c0c0b=1;$V865c0c0b<11;$V865c0c0b+=2){if (substr($V8fa14cdd,($V865c0c0b-1),1) == "0") {$Vbd19836d = $V77e77c6a ;}else{$Vbd19836d = $V5f44b105 ;}?>
src=<?php echo base_url(); ?>assets/plugins/boleto/p.gif width=<?php echo $Vbd19836d; ?> height=<?php echo $V2c9890f4; ?> border=0><img
<?php
if (substr($V8fa14cdd,$V865c0c0b,1) == "0") {$V3667f6a0 = $V77e77c6a ;}else{$V3667f6a0 = $V5f44b105 ;}?>
src=<?php echo base_url(); ?>assets/plugins/boleto/b.gif width=<?php echo $V3667f6a0; ?> height=<?php echo $V2c9890f4; ?> border=0><img
<?php
}}?>
src=<?php echo base_url(); ?>assets/plugins/boleto/p.gif width=<?php echo $V5f44b105; ?> height=<?php echo $V2c9890f4; ?> border=0><img
src=<?php echo base_url(); ?>assets/plugins/boleto/b.gif width=<?php echo $V77e77c6a; ?> height=<?php echo $V2c9890f4; ?> border=0><img
src=<?php echo base_url(); ?>assets/plugins/boleto/p.gif width=<?php echo 1; ?> height=<?php echo $V2c9890f4; ?> border=0>
<?php
}function Ff2317ae6($V0842f867,$V005480c8){return substr($V0842f867,0,$V005480c8);}function F0835e508($V0842f867,$V005480c8){return substr($V0842f867,strlen($V0842f867)-$V005480c8,$V005480c8);}
?>
