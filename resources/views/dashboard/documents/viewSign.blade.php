@extends('layouts.dashboard', ['section' => 'Documents'])

@section('styles')
<style>
    #pdf-container {
        display: flex;
        position: relative;
        justify-content: center;
        align-items: center;
    }

    #pdf-canvas {
        border: 1px solid black;
        width: 100%;
        height: 100%;
        max-width: 1000px;
    }

    #fabric-canvas {
        position: absolute;
        top: 0;
        left: 0;
        pointer-events: none;
    }

    .canvas-container {
        position: absolute !important;
        top: 0 !important;
    }

    .btn-blue {
        background-color: blue;
        color: white;
    }

    .btn-blue:hover {
        background-color: darkblue;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <button class="btn btn-dark me-2" onclick="changeBrushColor('black')"><i class='bx bxs-pen'></i></button>
        <button class="btn btn-danger me-2" onclick="changeBrushColor('red')"><i class='bx bxs-pen'></i></button>
        <button class="btn btn-blue me-2" onclick="changeBrushColor('blue')"><i class='bx bxs-pen'></i></button>
        <button class="btn btn-primary me-2" onclick="clearCanvas()"><i class='bx bxs-eraser'></i></button>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary" id="submitDocument"><i class='bx bx-save'></i> Sign document</button>
    </div>
</div>
<div id="pdf-container" class="text-center pb-3"></div>
<div class="navigation-buttons text-center pb-3">
    <button class="btn prev-page btn-primary me-2" disabled><</button>
    <button class="btn next-page btn-primary">></button>
</div>
@endsection

@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';
    window.jsPDF = window.jspdf.jsPDF;
    let pdfDoc = null;
    let currentPage = 1;
    let fabricCanvas = null;
    const pageCanvases = {};

    $(document).ready(function() {
        loadPdfFromUrl("{{ asset('storage/' . $document->path) }}");

        $('.prev-page').click(() => {
            if (currentPage > 1) {
                saveCanvasState(currentPage);
                currentPage--;
                renderPage(currentPage);
            }
        });

        $('.next-page').click(() => {
            if (currentPage < pdfDoc.numPages) {
                saveCanvasState(currentPage);
                currentPage++;
                renderPage(currentPage);
            }
        });

        $('.prev-page, .next-page').click(() => {
            $('.prev-page').prop('disabled', currentPage === 1);
            $('.next-page').prop('disabled', currentPage === pdfDoc.numPages);
        });

        // Llamar a esta función en el botón submit
        $('#submitDocument').click(() => {
            getPdfSigned();
        });
    });

    function loadPdfFromUrl(url) {
        pdfjsLib.getDocument(url).promise.then(pdf => {
            pdfDoc = pdf;
            renderPage(currentPage);
            $('.next-page').prop('disabled', currentPage === pdfDoc.numPages);
        }).catch(error => {
            console.error('Error loading PDF:', error);
        });
    }

    async function renderPage(pageNum) {
        await pdfDoc.getPage(pageNum).then(page => {
            const viewport = page.getViewport({ scale: 1 });
            $('#pdf-container').html('');

            const $pdfPageDiv = $('<div>').addClass('pdf-page')
                .css('width', `${viewport.width}px`)
                .css('height', `${viewport.height}px`);

            const $pdfCanvas = $('<canvas>').attr('id', 'pdf-canvas')
                .attr('width', viewport.width)
                .attr('height', viewport.height);

            const $fabricCanvasElement = $('<canvas>').attr('id', 'fabric-canvas')
                .addClass('fabric-canvas')
                .attr('width', viewport.width)
                .attr('height', viewport.height);

            $pdfPageDiv.append($pdfCanvas);
            $pdfPageDiv.append($fabricCanvasElement);
            $('#pdf-container').append($pdfPageDiv);

            const renderContext = {
                canvasContext: $pdfCanvas[0].getContext('2d'),
                viewport: viewport
            };
            page.render(renderContext).promise.then(() => {
                initializeFabric($fabricCanvasElement[0], pageNum);
            });
        });
    }

    function initializeFabric(canvasElement, pageNum) {
        fabricCanvas = new fabric.Canvas(canvasElement, {
            isDrawingMode: true,
            width: $(canvasElement).width(),
            height: $(canvasElement).height(),
            freeDrawingBrush: {
                color: 'black',
                width: 3
            }
        });

        if (pageCanvases[pageNum]) {
            fabricCanvas.loadFromJSON(pageCanvases[pageNum], () => {
                fabricCanvas.renderAll();
            });
        } else {
            pageCanvases[1] = fabricCanvas.toJSON();
        }
    }

    function saveCanvasState(pageNum) {
        if (fabricCanvas) {
            pageCanvases[pageNum] = fabricCanvas.toJSON();
        }
    }

    function clearCanvas() {
        if (fabricCanvas) {
            fabricCanvas.clear();
        }
    }

    function changeBrushColor(color) {
        if (fabricCanvas) {
            fabricCanvas.freeDrawingBrush.color = color;
        }
    }

    async function getPdfSigned() {
        if (!pdfDoc) {
            console.error('No se ha cargado ningún PDF.');
            return;
        }

        const doc = new jsPDF();
        const pagePromises = [];

        for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
            pagePromises.push(pdfDoc.getPage(pageNum).then(async (page) => {
                const viewport = page.getViewport({ scale: 1 });

                // Renderiza la página del PDF
                await renderPage(pageNum);

                // Usa html2canvas para capturar la imagen del contenedor completo
                const container = document.querySelector('.pdf-page');
                const canvas = await html2canvas(container, { scale: 1 });
                console.log(canvas)
                const imgData = canvas.toDataURL('image/jpeg', 1.0);

                // Crear una nueva página en el documento jsPDF
                if (pageNum > 1) {
                    doc.addPage();
                }
                doc.addImage(imgData, 'JPEG', 0, 0, doc.internal.pageSize.width, doc.internal.pageSize.height);

                // Guardar el documento PDF al finalizar todas las páginas
                if (pageNum === pdfDoc.numPages) {
                    const pdfBlob = doc.output('blob');
                    const formData = new FormData();
                    formData.append('pdf', pdfBlob, 'signed_document.pdf');
                    savePdfToBackend(formData);
                }
            }));
        }

        await Promise.all(pagePromises);
    }

    // Función para enviar el archivo al backend
    function savePdfToBackend(formData) {
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '{{ route('dashboard.documents.sign', $document->id) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                window.location.href = '{{ route('dashboard.documents.list', $folder->id) }}';
            },
            error: function(error) {
                //window.location.href = '{{ route('dashboard.documents.list', $folder->id) }}';
                console.log(error)
            }
        });
    }

</script>
@endsection