<style>
    /* Style for the popup */
    #drop-area {
            width: 100%;
            height: 100px;
            border: 2px dashed #ccc;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
            cursor: pointer;
        }

        /* Highlight the drop area when dragging over it */
        #drop-area.dragged-over {
            background-color: #f0f8ff;
        }
        #file-label {
            cursor: pointer;
        }

        /* Hide the file input */
        #file-input {
            display: none;
        }
    #popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            max-width: 600px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        /* Style for the overlay */
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }

        /* Style for header */
        #popup header {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        /* Style for footer */
        #popup footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
        }

        /* Style for content area */
        #popup .content {
            padding: 20px;
            display: flex;
            flex-direction: row;
        }

        /* Style for left content (upload image) */
        #popup .content .left-content {
            width: 30%;
        }

        /* Style for right content (image gallery) */
        #popup .content .right-content {
            width: 70%;
            overflow: auto;
        }

        /* Style for image in the gallery */
        #popup .content .right-content img {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }

        /* Style for delete button */
        #popup .content .right-content button {
            background-color: #ff5c5c;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        
</style>
 

<button onclick="openPopup()">Select Image</button>
<input name="{{$name}}" type='hidden' id='nameinput' />
<div class="myProfile-avatar-cover-image Browse-result">
    <div class="img-outer-no d-flex align-items-center">
       <img id='previewonselect' src="">
    </div>
 </div>
<div id="overlay"></div>

<div id="popup">
    <header>
        Select Image
    </header>
    <div class="content">
        <div class="left-content">
            <label id="file-label" for="file-input">
                <div id="drop-area" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)">
                    <p>Drop image here or click to upload</p>
                </div>
            </label>
            <input type="file" multiple id="file-input" accept="image/*" onchange="handleFileSelect(event)">
      
        </div>
        <div class="right-content">
            <!-- Image gallery can be dynamically added here -->
            @foreach($assets['files'] as $k=>$asset)
                <div   class="image-container">
                    <img onclick="onSelect('{{$assets['storage'][$k]}}','{{$asset}}')" src="{{$asset}}" alt="Image 1" >
                    <button onclick="deleteImage('{{$asset}}',this)">Delete</button>
                </div>
             @endforeach
            <!-- Add more images as needed -->
        </div>
    </div>
    <footer>
        <button onclick="closePopup()">Close</button>
        <button>Select</button>
    </footer>
</div>

<script>
// Function to open the popup
function openPopup() {
    document.getElementById('popup').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

// Function to close the popup
function closePopup() {
    document.getElementById('popup').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}
function deleteImage(imageId,e) {
        // Logic to delete the image with the specified ID
        console.log(`Deleting image with ID ${imageId}`);
        fetch('{{url("/admin/cms-pages/remove-asset")}}?id='+imageId)
        
        .then(data => {
            e.parentElement.remove()
            // You can handle the server response here
        })
        .catch(error => {
            console.error('Error uploading file:', error);
        });
    }
    function handleFileSelect(event) {
        // Handle file selection from the file input
        const files = event.target.files;
        handleFiles(files);
    }

    function handleDrop(event) {
        // Prevent default behavior (Prevent file from being opened)
        event.preventDefault();

        // Handle files dropped into the drop area
        const files = event.dataTransfer.files;
        handleFiles(files);

        // Reset the drop area's style
        document.getElementById('drop-area').classList.remove('dragged-over');
    }

    function handleDragOver(event) {
        // Prevent default behavior (Prevent file from being opened)
        event.preventDefault();

        // Highlight the drop area when dragging over it
        document.getElementById('drop-area').classList.add('dragged-over');
    }

    function handleDragLeave(event) {
        // Reset the drop area's style when dragging leaves it
        document.getElementById('drop-area').classList.remove('dragged-over');
    }

    function handleFiles(files) {
        // Handle the selected files (e.g., upload or display them)
        for (const file of files) {
            UploadFile(file);
            // You can add logic to upload or display the file here
        }
    }
    function onSelect(file,image){
        document.getElementById("nameinput").value = file;
        document.getElementById("previewonselect").src = image;
        // document.querySelector({{$preview}}).innerHTML='<img sr'

    }
    function UploadFile(file) {
        // Handle the selected files (e.g., upload or display them)
       
         const formData = new FormData();
        formData.append('upload[]', file);

        fetch('{{url("/admin/cms-pages/upload")}}', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            appendImageToContainer(data.data,data.file)
            // You can handle the server response here
        })
        .catch(error => {
            console.error('Error uploading file:', error);
        });
    }
    function appendImageToContainer(filename,storage) {
        // Create a new image element
        const img = document.createElement('img');
        img.src =   filename;  // Change the path if necessary
        img.alt = 'Uploaded Image';
        
        // Create a container for the image with a delete button
        const container = document.createElement('div');
        container.classList.add('image-container');
        container.appendChild(img);
        
        // Create a delete button for the image
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.onclick = function() {
            // Handle the delete button click event
            deleteImage(filename,this);
            container.remove(); // Remove the container when the delete button is clicked
        };
        img.onclick =  
        function(){
            onSelect(storage,filename)
        } 

        // Append the delete button to the container
        container.appendChild(deleteButton);

        // Append the container to the right content area
        document.querySelector('.right-content').appendChild(container);
    }
</script>

 

 
  