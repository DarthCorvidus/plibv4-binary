<?php
namespace plibv4\binary;
enum ByteOrder {
	#case SE
	// Little Endian = Six-Teen (as on x86)
	case LE;
	// Big Endian = Twenty-Six (as on Motorola)
	case BE;
}