@props([
    'target' => null, 
    'title' => null, 
    'type' => 'page',
    'text' => 'Imprimer',
    'icon' => 'fas fa-print',
    'class' => 'btn btn-secondary'
])

<button type="button" class="{{ $class }}" onclick="printDocument()">
    <i class="{{ $icon }} me-2"></i> {{ $text }}
</button>

@push('scripts')
<script>
function printDocument() {
    @if($type == 'section' && $target)
        printSection('{{ $target }}', '{{ $title }}');
    @elseif($type == 'table' && $target)
        printTable('{{ $target }}', '{{ $title }}');
    @else
        printPage();
    @endif
}

function printSection(elementId, title) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const printWindow = window.open('', '_blank');
    const content = element.cloneNode(true);
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${title}</title>
            <meta charset="UTF-8">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { padding: 20px; font-family: Arial, sans-serif; }
                @media print { body { padding: 0; } }
                .no-print { display: none; }
            </style>
        </head>
        <body>
            ${content.outerHTML}
            <script>window.print(); setTimeout(() => window.close(), 500);<\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

function printTable(tableId, title) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${title}</title>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                @media print { body { padding: 0; } }
            </style>
        </head>
        <body>
            <h2>${title}</h2>
            <p>Date: ${new Date().toLocaleDateString('fr-FR')}</p>
            ${table.outerHTML}
            <script>window.print();<\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

function printPage() {
    window.print();
}
</script>
@endpush