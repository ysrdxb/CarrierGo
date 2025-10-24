<?php

namespace App\Http\Controllers;
use PDF;
use App\Models\TransportOrder;
use App\Models\Reference;
use App\Models\DriverAuthorization;
use App\Models\Order;
use App\Models\Guarantee;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\IncomingInvoice;
use App\Mail\TransportOrderMail;
use App\Mail\DriverAuthorizationMail;
use App\Mail\OrderMail;
use App\Mail\GuaranteeMail;
use App\Mail\InvoiceMail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReferenceController extends Controller
{
    public function download_transport_order($id)
    {
        $transportOrder = TransportOrder::findOrFail($id);
        if ($transportOrder) {
			return view('pdf.transport-order', compact('transportOrder'));
            // $pdf = PDF::loadView('pdf.transport-order', compact('transportOrder'));
            // return $pdf->download('transport_order_'.$id.'.pdf');
        } else {
        }
    }   
    
    public function sendMail_transport_order($id)
    {
        $transportOrder = TransportOrder::findOrFail($id);
        $reference = Reference::findOrFail($transportOrder->reference->id);
        if ($transportOrder) {
            // $pdf = PDF::loadView('pdf.transport-order', compact('transportOrder'))->setOptions(['defaultFont' => 'sans-serif']);
            // $pdfData = $pdf->output();
            
            Mail::to($reference->creator->email)->send(new TransportOrderMail($transportOrder));
            
            return redirect()->back()->with('success', 'PDF sent successfully via email.');
        } else {
            return redirect()->back()->with('error', 'Transport order not found.');
        }
    }    

    public function download_driver_authorization($id)
    {
        $data = DriverAuthorization::findOrFail($id);
        if ($data) {
			return view('pdf.driver-authorization', compact('data'));
            // $pdf = PDF::loadView('pdf.driver-authorization', compact('data'));
            // return $pdf->download('driver_authorization_'.$id.'.pdf');
        } else {
        }
    }  
    
    public function sendMail_driver_authorization($id)
    {
        $data = DriverAuthorization::findOrFail($id);
        $reference = Reference::findOrFail($data->reference->id);
        if ($data) {
			// return view('pdf.driver-authorization', compact('order'));
            // $pdf = PDF::loadView('pdf.driver-authorization', compact('data'))->setOptions(['defaultFont' => 'sans-serif']);
            // $pdfData = $pdf->output();
            
            Mail::to($reference->creator->email)->send(new DriverAuthorizationMail($data));
            
            return redirect()->back()->with('success', 'PDF sent successfully via email.');
        } else {
            return redirect()->back()->with('error', 'Data not found.');
        }
    }     

    public function download_order($id)
    {
        $order = Order::findOrFail($id);
        if ($order) {
            return view('pdf.order_detail', compact('order'));
            // $pdf = PDF::loadView('pdf.order_detail', compact('order'))->setOptions(['defaultFont' => 'sans-serif']);
            // return $pdf->download('order_'.$id.'.pdf');
        } else {
        }
    }
    
    public function sendMail_order($id)
    {
        $order = Order::findOrFail($id);
        $reference = Reference::findOrFail($order->reference->id);
        if ($order) {

            // $pdf = PDF::loadView('emails.order', compact('order'))->setOptions(['defaultFont' => 'sans-serif']);
            // $pdfData = $pdf->output();
            
            Mail::to($reference->creator->email)->send(new OrderMail($order));
            
            return redirect()->back()->with('success', 'PDF sent successfully via email.');
        } else {
            return redirect()->back()->with('error', 'Order not found.');
        }
    }

    public function download_guarantee($id)
    {
        $guarantee = Guarantee::find($id);
        if ($guarantee) {
			return view('pdf.guarantee', compact('guarantee'));
            // $pdf = PDF::loadView('pdf.guarantee', compact('guarantee'));
            // return $pdf->download('guarantee_'.$id.'.pdf');
        } else {
            return redirect()->back()->with('error', 'Not found');
        }
    }
    
    public function sendMail_guarantee($id)
    {
        $guarantee = Guarantee::findOrFail($id);
        $reference = Reference::findOrFail($guarantee->reference->id);

        if ($guarantee) {
            // $pdf = PDF::loadView('pdf.guarantee', compact('guarantee'))->setOptions(['defaultFont' => 'sans-serif']);
            // $pdfData = $pdf->output();
            Mail::to($reference->creator->email)->send(new GuaranteeMail($guarantee));
            
            return redirect()->back()->with('success', 'PDF sent successfully via email.');
        } else {
            return redirect()->back()->with('error', 'Data not found.');
        }
    }    
    
    public function download_invoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice) {
			return view('pdf.invoice', compact('invoice'));						
            // $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
            // return $pdf->download('invoice_'.$id.'.pdf');
        } else {
        }
    }

    public function download_incinvoice($id)
    {
        $invoice = IncomingInvoice::findOrFail($id);
        if ($invoice) {
			return view('pdf.incinvoice', compact('invoice'));			
            $pdf = PDF::loadView('pdf.incinvoice', compact('invoice'));
            return $pdf->download('invoice_'.$id.'.pdf');
        } else {
        }
    }    

    public function downloadIncivoiceDocument($id)
    {
        $invoice = IncomingInvoice::findOrFail($id);
        if ($invoice) {
            return response()->download(storage_path('app/public/' . $invoice->receipt_file));
        } else {
        }
    }    
    
    public function sendMail_invoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        $reference = Reference::findOrFail($invoice->reference->id);

        if ($invoice) {
            // $pdf = PDF::loadView('pdf.invoice', compact('invoice'))->setOptions(['defaultFont' => 'sans-serif']);
            // $pdfData = $pdf->output();
            
            Mail::to($reference->creator->email)->send(new InvoiceMail($invoice));
            
            return redirect()->back()->with('success', 'PDF sent successfully via email.');
        } else {
            return redirect()->back()->with('error', 'Data not found.');
        }
    }      

    public function download_document($id)
    {
        $document = Document::findOrFail($id);
        if ($document) {
            return response()->download(storage_path('app/public/' . $document->document_path));
        } else {
        }
    }    
    
    public function sendMail_document($id)
    {
        return back();
    }     
    
}
