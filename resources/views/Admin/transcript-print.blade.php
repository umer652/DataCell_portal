<!DOCTYPE html>
<html>
<head>
    <title>Print Transcript - {{ $student->name }}</title>
    <style>
        /* Same as PDF view */
        @media print {
            body {
                margin: 0;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
        
        .print-btn {
            background: #0f1b5c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        
        .print-btn:hover {
            background: #1a2a7a;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center;">
        <button class="print-btn" onclick="window.print()">
            <i class="fas fa-print"></i> Print Transcript
        </button>
        <button class="print-btn" onclick="window.close()">
            <i class="fas fa-times"></i> Close
        </button>
    </div>
    
    <!-- Same content as PDF view -->
    <div class="header">
        <h2>UNIVERSITY ACADEMIC TRANSCRIPT</h2>
        <p>Official Transcript of Records</p>
    </div>
    
    <!-- Rest of the content same as PDF view -->
    
    <script>
        // Auto open print dialog
        window.onload = function() {
            // Uncomment to auto-open print dialog
            // window.print();
        }
    </script>
</body>
</html>