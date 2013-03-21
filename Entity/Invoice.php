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
 * Invoice Entity
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-05-07
 *
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *    "Order"      = "OrderInvoice",
 *    "HubFee"     = "HubFeeInvoice",
 *    "PostingFee" = "PostingFeeInvoice"
 * })
 * @ORM\Table(name="invoice")
 */
abstract class Invoice
{
    /**
     * Statuses
     *
     * @var int
     */
    const
        STATUS_POSTED   = 1
    ;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Profile", inversedBy="invoiceAsVendor")
     * @ORM\JoinColumn(name="vendor_id", referencedColumnName="id")
     */
    protected $vendor;

    /**
     * @ORM\ManyToOne(targetEntity="\HarvestCloud\CoreBundle\Entity\Profile", inversedBy="invoiceAsCustomer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $amount = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status_code = self::STATUS_POSTED;

    /**
     * @ORM\OneToMany(targetEntity="\HarvestCloud\DoubleEntryBundle\Entity\Journal\InvoiceJournal", mappedBy="invoice", cascade={"persist"})
     */
    protected $journals;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $posted_at;

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @param  decimal $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Get amount
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @return decimal
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set status_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @param integer $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->status_code = $statusCode;
    }

    /**
     * Get status_code
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-05-07
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->journals = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add journal
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-23
     *
     * @param  \HarvestCloud\DoubleEntryBundle\Entity\Journal\InvoiceJournal $journal
     *
     * @return Invoice
     */
    public function addJournal(\HarvestCloud\DoubleEntryBundle\Entity\Journal\InvoiceJournal $journal)
    {
        $this->journals[] = $journal;

        $journal->setInvoice($this);

        return $this;
    }

    /**
     * Remove journal
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-23
     *
     * @param \HarvestCloud\DoubleEntryBundle\Entity\Journal\InvoiceJournal $journal
     */
    public function removeJournal(\HarvestCloud\DoubleEntryBundle\Entity\Journal\InvoiceJournal $journal)
    {
        $this->journals->removeElement($journal);
    }

    /**
     * Get journals
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-02-23
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournals()
    {
        return $this->journals;
    }

    /**
     * Set vendor
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Profile $vendor
     *
     * @return Invoice
     */
    public function setVendor(\HarvestCloud\CoreBundle\Entity\Profile $vendor = null)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Get vendor
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set customer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @param  \HarvestCloud\CoreBundle\Entity\Profile $customer
     *
     * @return Invoice
     */
    public function setCustomer(\HarvestCloud\CoreBundle\Entity\Profile $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @return \HarvestCloud\CoreBundle\Entity\Profile
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set posted_at
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @param  \DateTime $postedAt
     *
     * @return Invoice
     */
    public function setPostedAt(\DateTime $postedAt)
    {
        $this->posted_at = $postedAt;

        return $this;
    }

    /**
     * Get posted_at
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     *
     * @return \DateTime
     */
    public function getPostedAt()
    {
        return $this->posted_at;
    }

    /**
     * post()
     *
     * Post all Journals (and therefore Postings) for this Invoice
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-03-19
     */
    public function post()
    {
        $this->setPostedAt(new \DateTime());

        foreach ($this->getJournals() as $journal) {
            $journal->post();
        }
    }
}
