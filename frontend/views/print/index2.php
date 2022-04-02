<?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



try {
    // Enter the share name for your USB printer here
    $connector = null;
    $connector = new WindowsPrintConnector("termal");

    /* Print a "Hello world" receipt" */
    $printer = new Printer($connector);


    /* Text */


    /* Double-strike (looks basically the same as emphasis) */


    /* Bit image */
    try {
        $modes = array(
            Printer::MODE_FONT_B,
            Printer::MODE_EMPHASIZED,
            Printer::MODE_DOUBLE_HEIGHT,
            Printer::MODE_DOUBLE_WIDTH,
            Printer::MODE_UNDERLINE);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT );
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH );

        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> text("hello Juli \n");
        $printer -> cut(Printer::CUT_FULL);
    } catch (Exception $e) {
        /* Images not supported on your PHP, or image file not found */
        $printer -> text($e -> getMessage() . "\n");
    }



    /* Always close the printer! On some PrintConnectors, no actual
     * data is sent until the printer is closed. */
    $printer -> close();

} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}



