<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Papery</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1779FC;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #1779FC;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .info-item {
            flex: 1;
        }
        
        .info-item label {
            font-weight: 600;
            color: #555;
            font-size: 12px;
            display: block;
            margin-bottom: 5px;
        }
        
        .info-item span {
            font-size: 14px;
            color: #333;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-card.blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-card.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .stat-card.orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .stat-card.purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }
        
        .stat-card label {
            font-size: 12px;
            opacity: 0.9;
            display: block;
            margin-bottom: 8px;
        }
        
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1779FC;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .bestseller-list {
            margin-bottom: 30px;
        }
        
        .bestseller-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #1779FC;
        }
        
        .bestseller-rank {
            width: 35px;
            height: 35px;
            background: #1779FC;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .bestseller-info {
            flex: 1;
        }
        
        .bestseller-info .title {
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }
        
        .bestseller-info .detail {
            font-size: 11px;
            color: #666;
        }
        
        .bestseller-sold {
            text-align: right;
            flex-shrink: 0;
        }
        
        .bestseller-sold .count {
            font-size: 24px;
            font-weight: bold;
            color: #1779FC;
        }
        
        .bestseller-sold .label {
            font-size: 11px;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #1779FC;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
        }
        
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 12px;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        tfoot td {
            font-weight: bold;
            background: #e5e7eb;
            padding: 12px 8px;
            border-top: 2px solid #1779FC;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge.sale {
            background: #d4edda;
            color: #155724;
        }
        
        .badge.return {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge.cash {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .badge.cashless {
            background: #cfe2ff;
            color: #084298;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #666;
            font-size: 12px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            @page {
                margin: 1cm;
            }
            
            .no-print {
                display: none;
            }
            
            .stat-card.blue {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .stat-card.green {
                background: #10b981 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .stat-card.orange {
                background: #f59e0b !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .stat-card.purple {
                background: #8b5cf6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            th {
                background: #1779FC !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .badge.sale {
                background: #d4edda !important;
                color: #155724 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .badge.return {
                background: #f8d7da !important;
                color: #721c24 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .badge.cash {
                background: #d1ecf1 !important;
                color: #0c5460 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .badge.cashless {
                background: #cfe2ff !important;
                color: #084298 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📚 PAPERY</h1>
        <p>Laporan Penjualan</p>
    </div>
    
    <div class="info-section">
        <div class="info-item">
            <label>Periode Laporan:</label>
            <span>{{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</span>
        </div>
        <div class="info-item">
            <label>Tanggal Cetak:</label>
            <span>{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</span>
        </div>
        <div class="info-item">
            <label>Dicetak Oleh:</label>
            <span>{{ Auth::user()->name }}</span>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card blue">
            <label>Total Transaksi</label>
            <div class="value">{{ number_format($total_transactions) }}</div>
        </div>
        <div class="stat-card green">
            <label>Total Pendapatan</label>
            <div class="value">Rp {{ number_format($total_revenue, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card orange">
            <label>Total Diskon</label>
            <div class="value">Rp {{ number_format($total_discount, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card purple">
            <label>Buku Terjual</label>
            <div class="value">{{ number_format($total_books_sold) }}</div>
        </div>
    </div>
    
    @if($best_sellers->count() > 0)
    <div class="bestseller-list">
        <h2 class="section-title">🔥 Top 5 Buku Terlaris</h2>
        @foreach($best_sellers as $index => $item)
        <div class="bestseller-item">
            <div class="bestseller-rank">{{ $index + 1 }}</div>
            <div class="bestseller-info">
                <div class="title">{{ $item->book->title ?? '-' }}</div>
                <div class="detail">
                    Stok: {{ $item->book->bookDetail->stock ?? 0 }} | 
                    Harga: Rp {{ number_format($item->book->bookDetail->price ?? 0, 0, ',', '.') }}
                </div>
            </div>
            <div class="bestseller-sold">
                <div class="count">{{ $item->total_sold }}</div>
                <div class="label">Terjual</div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    
    <h2 class="section-title">📋 Riwayat Transaksi</h2>
    
    @if($transactions->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 11%;">Tanggal</th>
                <th style="width: 13%;">Kode</th>
                <th style="width: 10%;">Kasir</th>
                <th style="width: 12%;">Pelanggan</th>
                <th style="width: 11%;">Total</th>
                <th style="width: 10%;">Diskon</th>
                <th style="width: 11%;">Subtotal</th>
                <th style="width: 9%;">Metode</th>
                <th style="width: 9%;">Jenis</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            @php
                $discount = 0;
                $total_price = $transaction->subtotal;
                
                if ($transaction->discount) {
                    $price_before_discount = $transaction->subtotal / (1 - ($transaction->discount->percentage / 100));
                    $discount = $price_before_discount * ($transaction->discount->percentage / 100);
                    $total_price = $price_before_discount;
                }
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transaction->transaction_date ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y H:i') : '-' }}</td>
                <td>{{ $transaction->transactionItems->pluck('transaction_code')->unique()->join(', ') }}</td>
                <td>{{ $transaction->user->name ?? '-' }}</td>
                <td>{{ $transaction->customer_name }}</td>
                <td>Rp {{ number_format($total_price, 0, ',', '.') }}</td>
                <td style="color: #dc2626;">Rp {{ number_format($discount, 0, ',', '.') }}</td>
                <td style="color: #059669; font-weight: bold;">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                <td>
                    <span class="badge {{ $transaction->payment_method }}">
                        {{ strtoupper($transaction->payment_method) }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $transaction->transaction_type }}">
                        {{ strtoupper($transaction->transaction_type) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;">GRAND TOTAL:</td>
                <td>Rp {{ number_format($transactions->sum(function($t) { 
                    $total = $t->subtotal;
                    if ($t->discount) {
                        $total = $t->subtotal / (1 - ($t->discount->percentage / 100));
                    }
                    return $total;
                }), 0, ',', '.') }}</td>
                <td style="color: #dc2626;">Rp {{ number_format($total_discount, 0, ',', '.') }}</td>
                <td style="color: #059669;">Rp {{ number_format($transactions->sum('subtotal'), 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
    @else
    <div class="no-data">
        <p style="font-size: 16px; margin-bottom: 5px;">📭</p>
        <p>Tidak ada transaksi pada periode ini</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Laporan ini dicetak secara otomatis oleh sistem Papery</p>
        <p>© {{ date('Y') }} Papery - Sistem Manajemen Toko Buku</p>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>