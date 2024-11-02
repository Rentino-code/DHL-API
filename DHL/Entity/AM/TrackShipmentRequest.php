<?php

namespace DHL\Entity\AM;

use DHL\Entity\Base;
use XMLWriter; // Make sure to include this if you are using it

class TrackShipmentRequest extends Base {
    public function __construct($siteId, $password, $trackingNumber) {
        parent::__construct(); // Ensure the parent constructor is called
        $this->SiteID = $siteId;
        $this->Password = $password;
        $this->TrackingNumber = $trackingNumber;
        $this->MessageReference = $this->generateMessageReference(); // Generate a unique message reference
    }

    /**
     * Generate a unique message reference.
     * @return string
     */
    private function generateMessageReference() {
        return substr(uniqid(rand(), true), 0, 32);
    }

    /**
     * Generate XML for the request.
     * @param XMLWriter|null $xmlWriter Optional XMLWriter instance to use for building XML.
     * @return string
     */
    public function toXML(XMLWriter $xmlWriter = null) {
        if ($xmlWriter === null) {
            $xmlWriter = new XMLWriter();
            $xmlWriter->openMemory();
            $xmlWriter->setIndent(true);
        }

        $xmlWriter->startElement('req:TrackShipmentRequest');
        $xmlWriter->writeAttribute('xmlns:req', 'http://www.dhl.com');

        $xmlWriter->writeElement('SiteID', $this->SiteID);
        $xmlWriter->writeElement('Password', $this->Password);
        $xmlWriter->writeElement('MessageReference', $this->MessageReference);
        
        $xmlWriter->startElement('TrackingRequest');
        $xmlWriter->writeElement('TrackingNumber', $this->TrackingNumber);
        $xmlWriter->endElement(); // Close TrackingRequest

        $xmlWriter->endElement(); // Close TrackShipmentRequest

        return $xmlWriter->outputMemory();
    }
}