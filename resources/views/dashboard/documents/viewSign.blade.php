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

    async function  renderPage(pageNum) {
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
        const pdf = new jsPDF();
        const formData = new FormData();

        for (let i = 1; i <= pdfDoc.numPages; i++) {
            saveCanvasState(i);

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            const page = await pdfDoc.getPage(i);
            const viewport = page.getViewport({ scale: 1 });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            // Renderizar la página del PDF en el canvas
            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            await page.render(renderContext).promise;

            const imgData = canvas.toDataURL('image/png'); // Obtener la imagen de la página del PDF
            const fabricCanvas = new fabric.Canvas(canvas, {
                width: canvas.width,
                height: canvas.height
            });

            // Cargar la firma de Fabric.js
            fabricCanvas.loadFromJSON(pageCanvases[i], () => {
                fabricCanvas.renderAll();

                // Dibuja la imagen de la página del PDF en el canvas de Fabric
                fabric.Image.fromURL(imgData, (img) => {
                    fabricCanvas.setBackgroundImage(img, fabricCanvas.renderAll.bind(fabricCanvas));

                    // Dibuja la firma en el canvas de Fabric
                    const signatureDataUrl = fabricCanvas.toDataURL('image/png');
                    const signatureImg = new Image();
                    signatureImg.src = signatureDataUrl;
                    signatureImg.onload = () => {
                        context.clearRect(0, 0, canvas.width, canvas.height); // Limpiar el canvas
                        context.drawImage(signatureImg, 0, 0); // Dibujar la firma sobre el fondo del PDF

                        // Añadir la imagen combinada al PDF
                        pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, 210, 297);

                        if (i === pdfDoc.numPages) {
                            const pdfBlob = pdf.output('blob');

                            formData.append('pdf', pdfBlob, 'signed.pdf')
                            savePdfToBackend(formData);
                        }
                    };
                });
            });
        }
    }


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
