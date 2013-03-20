<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderInvoice Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-07
 *
 * @ORM\Entity
 */
class OrderInvoice extends Invoice
{
    /**
     * @ORM\OneToOne(targetEntity="HarvestCloud\CoreBundle\Entity\Order", mappedBy="invoice")
     */
    protected $order;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-23
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Order $order
     */
    public function __construct(\HarvestCloud\CoreBundle\Entity\Order $order)
    {
        $this->setOrder($order);
        $this->setVendor($order->getSeller());
        $this->setCustomer($order->getBuyer());
    }

    /**
     * Set order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-23
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Order $order
     *
     * @return OrderInvoice
     */
    public function setOrder(\HarvestCloud\CoreBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-23
     *
     * @return \HarvestCloud\CoreBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * post()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-23
     */
    public function post()
    {
        $this->setPostedAt(new \DateTime());

        // Create Vendor Journal
        $vendorJournal = new \HarvestCloud\DoubleEntryBundle\Entity\Journal\InvoiceJournal($this);

        // Create Vendor A/R Posting
        $arPosting = new \HarvestCloud\DoubleEntryBundle\Entity\Posting();
        $arPosting->setAccount($this->getVendor()->getAccountsReceivableAccount());
        $arPosting->setAmount($this->getAmount());

        // Add Vendor A/R Posting to Vendor Journal
        $vendorJournal->addPosting($arPosting);

        // Create Vendor Sales Posting
        $salesPosting = new \HarvestCloud\DoubleEntryBundle\Entity\Posting();
        $salesPosting->setAccount($this->getVendor()->getSalesAccount());
        $salesPosting->setAmount(-1*$this->getAmount());

        // Add Vendor Sales Posting to Vendor Journal
        $vendorJournal->addPosting($salesPosting);

        // Add Vendor Journal to Invoice
        $this->addJournal($vendorJournal);


        // Create Customer Journal
        $customerJournal = new \HarvestCloud\DoubleEntryBundle\Entity\Journal\InvoiceJournal($this);

        // Create Customer A/P Posting
        $apPosting = new \HarvestCloud\DoubleEntryBundle\Entity\Posting();
        $apPosting->setAccount($this->getCustomer()->getAccountsPayableAccount());
        $apPosting->setAmount(-1*$this->getAmount());

        // Add Customer A/P Posting to Customer Journal
        $customerJournal->addPosting($apPosting);

        // Create Customer Purchases Posting
        $purchasePosting = new \HarvestCloud\DoubleEntryBundle\Entity\Posting();
        $purchasePosting->setAccount($this->getCustomer()->getPurchasesAccount());
        $purchasePosting->setAmount($this->getAmount());

        // Add Customer Purchases Posting to Customer Journal
        $customerJournal->addPosting($purchasePosting);

        // Add Customer Journal to Invoice
        $this->addJournal($customerJournal);
    }
}
