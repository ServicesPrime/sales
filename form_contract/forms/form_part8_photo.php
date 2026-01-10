<!-- ====================================== -->
<!-- 📸 Section 8: Photos -->
<!-- ====================================== -->

<div class="question-block" id="q29">
  <label class="question-label">
    <?= t('upload_photos', $lang) ?>
  </label>

  <div class="photo-upload-section">
    <div class="upload-instructions">
        <p>📸 <?= t('upload_instructions', $lang) ?></p>
    </div>
    
    <div class="photo-upload-container">
        <input 
            type="file" 
            id="photo-input" 
            name="photos[]" 
            accept="image/*" 
            capture="environment"
            multiple 
            style="display: none;"
        >
        
        <button type="button" class="add-photo-btn" id="add-photo-box">
            <span class="plus-icon">+</span>
            <?= t('add_photos', $lang) ?>
        </button>
        
        <div class="photo-preview-grid" id="photo-container">
            <!-- Las fotos se mostrarán aquí -->
        </div>
    </div>
  </div>
</div>

<style>
.photo-upload-section {
    margin-top: 15px;
}

.upload-instructions {
    margin-bottom: 20px;
    padding: 15px;
    background: #f0f7ff;
    border-left: 4px solid var(--primary-color);
    border-radius: 8px;
}

.upload-instructions p {
    margin: 0;
    color: var(--primary-color);
    font-weight: 500;
    font-size: 14px;
}

.photo-upload-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.add-photo-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px 30px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,31,84,0.2);
    width: fit-content;
}

.add-photo-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,31,84,0.3);
    background: linear-gradient(135deg, var(--primary-light) 0%, #004099 100%);
}

.plus-icon {
    font-size: 24px;
    font-weight: bold;
    line-height: 1;
}

.photo-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.photo-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    background: white;
}

.photo-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.photo-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}

.photo-card-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 8px;
}

.photo-card-btn {
    background: rgba(255,255,255,0.9);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.photo-card-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.photo-card-btn.delete {
    color: #dc3545;
}

.photo-card-btn.delete:hover {
    background: #dc3545;
    color: white;
}

.photo-info {
    padding: 12px;
    background: white;
}

.photo-filename {
    font-size: 13px;
    color: #1a202c;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.photo-size {
    font-size: 11px;
    color: #718096;
    margin-top: 4px;
}
</style>

<script>
(function() {
    'use strict';
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPhotoUpload);
    } else {
        initPhotoUpload();
    }
    
    function initPhotoUpload() {
        console.log('📸 Initializing photo upload module...');
        
        const addPhotoBtn = document.getElementById("add-photo-box");
        const photoInput = document.getElementById("photo-input");
        const photoContainer = document.getElementById("photo-container");
        
        if (!addPhotoBtn || !photoInput || !photoContainer) {
            console.error("❌ Photo upload elements not found");
            return;
        }
        
        // ✅ CRITICAL FIX: Use global array instead of DataTransfer
        window.photoFilesArray = window.photoFilesArray || [];
        
        // Click en botón abre file input
        addPhotoBtn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log('📂 Opening file picker...');
            photoInput.click();
        });
        
        // Cuando se seleccionan fotos
        photoInput.addEventListener("change", (e) => {
            console.log('📥 Files selected:', e.target.files.length);
            const files = Array.from(e.target.files);
            
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    console.log('✅ Adding photo:', file.name, file.size, 'bytes');
                    window.photoFilesArray.push(file);
                    addPhotoPreview(file);
                } else {
                    console.warn('⚠️ Skipping non-image file:', file.name);
                }
            });
            
            console.log('📊 Total photos now:', window.photoFilesArray.length);
            
            // ✅ CRITICAL: Update the input with accumulated files
            updatePhotoInput();
            
            // Clear the input value to allow re-selecting the same file
            e.target.value = '';
        });
        
        function addPhotoPreview(file) {
            const reader = new FileReader();
            
            reader.onload = (e) => {
                const photoCard = document.createElement("div");
                photoCard.className = "photo-card";
                photoCard.dataset.filename = file.name;
                photoCard.dataset.filesize = file.size;
                
                const safeFilename = file.name.replace(/'/g, "\\'");
                
                photoCard.innerHTML = `
                    <img src="${e.target.result}" alt="${safeFilename}">
                    <div class="photo-card-actions">
                        <button type="button" class="photo-card-btn delete" data-filename="${safeFilename}">
                            🗑️
                        </button>
                    </div>
                    <div class="photo-info">
                        <div class="photo-filename" title="${safeFilename}">${file.name}</div>
                        <div class="photo-size">${formatFileSize(file.size)}</div>
                    </div>
                `;
                
                // Add event listener to delete button
                const deleteBtn = photoCard.querySelector('.photo-card-btn.delete');
                deleteBtn.addEventListener('click', function() {
                    removePhoto(this);
                });
                
                photoContainer.appendChild(photoCard);
            };
            
            reader.readAsDataURL(file);
        }
        
        function removePhoto(button) {
            const filename = button.dataset.filename;
            console.log('🗑️ Removing photo:', filename);
            
            // Remove from preview
            const photoCard = button.closest('.photo-card');
            if (photoCard) {
                photoCard.remove();
            }
            
            // Remove from array
            window.photoFilesArray = window.photoFilesArray.filter(file => file.name !== filename);
            console.log('📊 Photos remaining:', window.photoFilesArray.length);
            
            // Update input
            updatePhotoInput();
        }
        
        // ✅ CRITICAL: Function to update the input with current files
        function updatePhotoInput() {
            const dt = new DataTransfer();
            window.photoFilesArray.forEach(file => {
                dt.items.add(file);
            });
            photoInput.files = dt.files;
            console.log('✅ Photo input updated. Files count:', photoInput.files.length);
        }
        
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }
        
        // ✅ EXPORT: Make available globally for save_draft
        window.getPhotoFiles = function() {
            return window.photoFilesArray;
        };
        
        window.clearPhotoFiles = function() {
            window.photoFilesArray = [];
            photoContainer.innerHTML = '';
            photoInput.value = '';
            updatePhotoInput();
            console.log('✅ Photos cleared');
        };
        
        console.log('✅ Photo upload module initialized successfully');
    }
})();
</script>