<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SoftDeletesTrait;

class Reference extends Model
{
    use HasFactory, SoftDeletes, SoftDeletesTrait;

    protected $table = 'reference';

    protected $guarded = [];

    // Define constants for status
    const STATUS_NEW = 'New';
    const STATUS_BOOKED = 'Booked';
    const STATUS_PICKUP_SCHEDULED = 'Pickup scheduled';
    const STATUS_PICKED_UP = 'Picked up';
    const STATUS_PORT_DELIVERED = 'Port delivered';
    const STATUS_READY_TO_SHIP = 'Ready to ship';
    const STATUS_SHIPPED = 'Shipped';
    const STATUS_ARRIVED = 'Arrived';
    const STATUS_PAID = 'Paid';
    const STATUS_RELEASED = 'Released';
	const STATUS_PENDING = 'PENDING';
	const STATUS_CANCELLED = 'CANCELLED';
	const STATUS_SELF_DELIVERY = 'Self Delivery';
    // Relationship methods...

    public function markAsNew()
    {
        $this->status = self::STATUS_NEW;
        $this->save();
    }

    public function markAsBooked()
    {
        $this->status = self::STATUS_BOOKED;
        $this->save();
    }

    public function markAsPickupScheduled()
    {
        $this->status = self::STATUS_PICKUP_SCHEDULED;
        $this->save();
    }
	
    public function markAsSelfDelivery()
    {
        $this->status = self::STATUS_SELF_DELIVERY;
        $this->save();
    }	

    public function markAsPickedUp()
    {
        $this->status = self::STATUS_PICKED_UP;
        $this->save();
    }

    public function markAsPortDelivered()
    {
        $this->status = self::STATUS_PORT_DELIVERED;
        $this->save();
    }

    public function markAsReadyToShip()
    {
        $this->status = self::STATUS_READY_TO_SHIP;
        $this->save();
    }

    public function markAsShipped()
    {
        $this->status = self::STATUS_SHIPPED;
        $this->save();
    }

    public function markAsArrived()
    {
        $this->status = self::STATUS_ARRIVED;
        $this->save();
    }

    public function markAsPaid()
    {
        $this->status = self::STATUS_PAID;
        $this->save();
    }

    public function markAsReleased()
    {
        $this->status = self::STATUS_RELEASED;
        $this->save();
    }    

    

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function consignee()
    {
        return $this->belongsTo(DatabaseEntry::class, 'consignee_id');
    }  

    public function client()
    {
        return $this->belongsTo(DatabaseEntry::class, 'client_id');
    }  

    public function agent()
    {
        return $this->belongsTo(DatabaseEntry::class, 'agent_id');
    }  

    public function carrier()
    {
        return $this->belongsTo(DatabaseEntry::class, 'carrier_id');
    }  

    public function merchant()
    {
        return $this->belongsTo(DatabaseEntry::class, 'merchant_id');
    }  

    public function freights()
    {
        return $this->hasMany(Freight::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'reference_id');
    }    

    public function transportOrder()
    {
        return $this->hasMany(TransportOrder::class, 'reference_id');
    }    

    public function guarantee()
    {
        return $this->hasOne(Guarantee::class, 'reference_id');
    }      

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'reference_id');
    }   
    
    public function driverAuthorization()
    {
        return $this->hasOne(DriverAuthorization::class, 'reference_id');
    }     

    public function order()
    {
        return $this->hasOne(Order::class, 'reference_id');
    }      

    public static function totalSum()
    {
        return static::sum('carrier_fees') + static::sum('agent_fees') + static::sum('price');
    }

    public function additionalFees()
    {
        return $this->hasMany(ReferenceAdditionalFee::class, 'reference_id');
    }

    public function editHistories()
    {
        return $this->hasMany(ReferencesEditHistory::class, 'reference_id');
    }

    public function incInvoices()
    {
        return $this->hasMany(IncInvoiceReference::class, 'reference_id');
    }
}
