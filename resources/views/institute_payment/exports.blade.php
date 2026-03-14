<!-- resources/views/institute-income/partials/exports.blade.php -->
@push('scripts')
<script>
    // Export Functions
    $('#exportPdfBtn').click(function () {
        if (!currentData) {
            showNotification('No data available to export', 'warning');
            return;
        }
        exportToPdf(currentData);
    });

    $('#exportExcelBtn').click(function () {
        if (!currentData) {
            showNotification('No data available to export', 'warning');
            return;
        }
        exportToExcel(currentData);
    });

    // Export to PDF function
    function exportToPdf(data) {
        // Create a new window for PDF generation
        const printWindow = window.open('', '_blank');
        
        const month = currentMonth;
        const formattedMonth = new Date(month + '-01').toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long' 
        });
        
        // Get summary data
        const summary = data.summary || data;
        const teachers = data.data || [];
        
        // Generate HTML for PDF
        const pdfContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Institute Income Report - ${formattedMonth}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 12px;
                        color: #333;
                        padding: 20px;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 30px;
                        border-bottom: 2px solid #4e73df;
                        padding-bottom: 10px;
                    }
                    .header h1 {
                        color: #4e73df;
                        margin: 0;
                        font-size: 24px;
                    }
                    .header .subtitle {
                        color: #666;
                        font-size: 14px;
                    }
                    .summary-section {
                        margin-bottom: 30px;
                    }
                    .summary-section h2 {
                        color: #4e73df;
                        border-bottom: 1px solid #ddd;
                        padding-bottom: 5px;
                        font-size: 18px;
                    }
                    .summary-cards {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 10px;
                        margin-top: 15px;
                    }
                    .summary-card {
                        border: 1px solid #ddd;
                        padding: 10px;
                        border-radius: 5px;
                        background: #f9f9f9;
                    }
                    .summary-card .label {
                        font-weight: bold;
                        color: #666;
                        font-size: 10px;
                        text-transform: uppercase;
                    }
                    .summary-card .value {
                        font-size: 16px;
                        color: #333;
                        margin-top: 5px;
                    }
                    .table-section {
                        margin-top: 30px;
                    }
                    .table-section h2 {
                        color: #4e73df;
                        border-bottom: 1px solid #ddd;
                        padding-bottom: 5px;
                        font-size: 18px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 15px;
                    }
                    th {
                        background-color: #f8f9fa;
                        border: 1px solid #dee2e6;
                        padding: 8px;
                        text-align: left;
                        font-weight: bold;
                        color: #495057;
                    }
                    td {
                        border: 1px solid #dee2e6;
                        padding: 8px;
                    }
                    tr:nth-child(even) {
                        background-color: #f8f9fa;
                    }
                    .text-end {
                        text-align: right;
                    }
                    .footer {
                        margin-top: 40px;
                        text-align: center;
                        color: #666;
                        font-size: 10px;
                        border-top: 1px solid #ddd;
                        padding-top: 10px;
                    }
                    .positive {
                        color: #198754;
                    }
                    .negative {
                        color: #dc3545;
                    }
                    @media print {
                        .no-print {
                            display: none;
                        }
                        body {
                            padding: 0;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Institute Income Report</h1>
                    <div class="subtitle">${formattedMonth}</div>
                    <div class="subtitle">Generated on ${new Date().toLocaleDateString()} at ${new Date().toLocaleTimeString()}</div>
                </div>
                
                <div class="summary-section">
                    <h2>Summary</h2>
                    <div class="summary-cards">
                        <div class="summary-card">
                            <div class="label">Total Payments</div>
                            <div class="value">Rs ${formatNumber(summary.total_teacher_payments || 0)}</div>
                        </div>
                        <div class="summary-card">
                            <div class="label">Teacher Earnings</div>
                            <div class="value">Rs ${formatNumber(summary.total_teacher_earnings || 0)}</div>
                        </div>
                        <div class="summary-card">
                            <div class="label">Teacher Advances</div>
                            <div class="value">Rs ${formatNumber(summary.total_teacher_advances || 0)}</div>
                        </div>
                        <div class="summary-card">
                            <div class="label">Teacher Salaries</div>
                            <div class="value">Rs ${formatNumber(summary.total_teacher_salaries || 0)}</div>
                        </div>
                        <div class="summary-card">
                            <div class="label">Teacher Net Earnings</div>
                            <div class="value">Rs ${formatNumber(summary.total_teacher_net_earnings || 0)}</div>
                        </div>
                        <div class="summary-card">
                            <div class="label">Institute Income</div>
                            <div class="value">Rs ${formatNumber(summary.total_institute_from_classes || 0)}</div>
                        </div>
                        <div class="summary-card">
                            <div class="label">Admission Payments</div>
                            <div class="value">Rs ${formatNumber(summary.admission_payments || 0)}</div>
                        </div>
                        <div class="summary-card">
                            <div class="label">Extra Income</div>
                            <div class="value">Rs ${formatNumber(summary.extra_income_for_month || 0)}</div>
                        </div>
                        <div class="summary-card">
                            <div class="label">Institute Expenses</div>
                            <div class="value">Rs ${formatNumber(summary.total_institute_expenese || 0)}</div>
                        </div>
                        <div class="summary-card">
                            <div class="label">Institute Gross Income</div>
                            <div class="value">Rs ${formatNumber(summary.institute_gross_income || 0)}</div>
                        </div>
                        <div class="summary-card">
                            <div class="label">Institute Net Income</div>
                            <div class="value">Rs ${formatNumber(summary.institute_net_income || 0)}</div>
                        </div>
                    </div>
                </div>
                
                <div class="table-section">
                    <h2>Teacher Income Details</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Teacher</th>
                                <th>Teacher ID</th>
                                <th class="text-end">Payments</th>
                                <th class="text-end">Teacher Salary</th>
                                <th class="text-end">Advance</th>
                                <th class="text-end">Net Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${teachers.map((teacher, index) => {
                                const netEarning = parseFloat(teacher.teacher_net_earning || 0);
                                const netEarningClass = netEarning > 0 ? 'positive' : 
                                                       netEarning < 0 ? 'negative' : '';
                                
                                return \`
                                    <tr>
                                        <td>\${index + 1}</td>
                                        <td>\${teacher.teacher_name}</td>
                                        <td>\${teacher.teacher_id}</td>
                                        <td class="text-end">Rs \${formatNumber(teacher.total_payments_this_month)}</td>
                                        <td class="text-end">Rs \${formatNumber(teacher.teacher_total_earning)}</td>
                                        <td class="text-end">Rs \${formatNumber(teacher.teacher_advance)}</td>
                                        <td class="text-end \${netEarningClass}">Rs \${formatNumber(teacher.teacher_net_earning)}</td>
                                    </tr>
                                \`;
                            }).join('')}
                        </tbody>
                    </table>
                </div>
                
                <div class="footer">
                    <p>This report was generated automatically from the Institute Income Management System.</p>
                    <p>© \${new Date().getFullYear()} Institute Management System</p>
                </div>
                
                <div class="no-print" style="margin-top: 20px; text-align: center;">
                    <button onclick="window.print()" style="padding: 10px 20px; background: #4e73df; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Print / Save as PDF
                    </button>
                    <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                        Close
                    </button>
                </div>
                
                <script>
                    // Auto-print after 1 second
                    setTimeout(() => {
                        window.print();
                    }, 1000);
                </script>
            </body>
            </html>
        `;
        
        printWindow.document.write(pdfContent);
        printWindow.document.close();
        showNotification('PDF report opened in new window. You can print or save as PDF.', 'success');
    }

    // Export to Excel function
    function exportToExcel(data) {
        const month = currentMonth;
        const formattedMonth = new Date(month + '-01').toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long' 
        });
        
        // Get summary data
        const summary = data.summary || data;
        const teachers = data.data || [];
        
        // Create Excel content
        let excelContent = 'Institute Income Report\\n';
        excelContent += \`\${formattedMonth}\\n\`;
        excelContent += \`Generated on \${new Date().toLocaleDateString()} at \${new Date().toLocaleTimeString()}\\n\\n\`;
        
        // Add summary section
        excelContent += 'SUMMARY\\n';
        excelContent += \`Total Payments,Rs \${summary.total_teacher_payments || 0}\\n\`;
        excelContent += \`Teacher Earnings,Rs \${summary.total_teacher_earnings || 0}\\n\`;
        excelContent += \`Teacher Advances,Rs \${summary.total_teacher_advances || 0}\\n\`;
        excelContent += \`Teacher Salaries,Rs \${summary.total_teacher_salaries || 0}\\n\`;
        excelContent += \`Teacher Net Earnings,Rs \${summary.total_teacher_net_earnings || 0}\\n\`;
        excelContent += \`Institute Income,Rs \${summary.total_institute_from_classes || 0}\\n\`;
        excelContent += \`Admission Payments,Rs \${summary.admission_payments || 0}\\n\`;
        excelContent += \`Extra Income,Rs \${summary.extra_income_for_month || 0}\\n\`;
        excelContent += \`Institute Expenses,Rs \${summary.total_institute_expenese || 0}\\n\`;
        excelContent += \`Institute Gross Income,Rs \${summary.institute_gross_income || 0}\\n\`;
        excelContent += \`Institute Net Income,Rs \${summary.institute_net_income || 0}\\n\\n\`;
        
        // Add teacher details section
        excelContent += 'TEACHER INCOME DETAILS\\n';
        excelContent += 'No.,Teacher Name,Teacher ID,Payments,Teacher Salary,Advance,Net Salary\\n';
        
        teachers.forEach((teacher, index) => {
            excelContent += \`\${index + 1},\`;
            excelContent += \`\${teacher.teacher_name},\`;
            excelContent += \`\${teacher.teacher_id},\`;
            excelContent += \`Rs \${teacher.total_payments_this_month || 0},\`;
            excelContent += \`Rs \${teacher.teacher_total_earning || 0},\`;
            excelContent += \`Rs \${teacher.teacher_advance || 0},\`;
            excelContent += \`Rs \${teacher.teacher_net_earning || 0}\\n\`;
        });
        
        // Add footer
        excelContent += '\\n';
        excelContent += 'Report generated from Institute Income Management System\\n';
        excelContent += \`© \${new Date().getFullYear()} Institute Management System\\n\`;
        
        // Create blob and download
        const blob = new Blob([excelContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', \`institute-income-report-\${month}.csv\`);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showNotification('Excel report downloaded successfully', 'success');
    }
</script>
@endpush