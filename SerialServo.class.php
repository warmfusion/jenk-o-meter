<?php 

include "php_serial.class.php";

class SerialServo
{

  var $serial;

  function SerialServo($device = "/dev/ttyUSB0"){
    // Let's start the class
    $this->serial = new phpSerial;
  
    // First we must specify the device. This works on both linux and windows (if
    // your linux serial device is /dev/ttyS0 for COM1, etc)
    $this->serial->deviceSet($device);

    // We can change the baud rate, parity, length, stop bits, flow control
    $this->serial->confBaudRate(57600);
    $this->serial->confParity("none");
    $this->serial->confCharacterLength(8);
    $this->serial->confStopBits(1);
    $this->serial->confFlowControl("none");

    // Then we need to open it
    $this->serial->deviceOpen();
  }
  
  function set($angle){
    $this->serial->sendMessage("4,$angle;");
    return $this->serial->readPort();
  }

  function get(){
    $this->serial->sendMessage("5;");
    return $this->serial->readPort();
  }

  function ping(){
    $this->serial->sendMessage("2;");
    return $this->serial->readPort();
  }
}
?>
