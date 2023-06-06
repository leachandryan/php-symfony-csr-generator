class CSRStorage {
    constructor() {
        this.storageKey = 'stored_csrs';
    }

    getAllCSRs() {
        const stored = localStorage.getItem(this.storageKey);
        return stored ? JSON.parse(stored) : [];
    }

    saveCSR(csrData) {
        const csrs = this.getAllCSRs();
        const newCSR = {
            id: Date.now(), // Use timestamp as unique ID
            ...csrData,
            createdAt: new Date().toISOString()
        };
        
        csrs.push(newCSR);
        localStorage.setItem(this.storageKey, JSON.stringify(csrs));
        return newCSR;
    }

    deleteCSR(csrId) {
        const csrs = this.getAllCSRs();
        const updatedCSRs = csrs.filter(csr => csr.id !== csrId);
        localStorage.setItem(this.storageKey, JSON.stringify(updatedCSRs));
    }

    clearAll() {
        localStorage.removeItem(this.storageKey);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const csrStorage = new CSRStorage();
    const form = document.querySelector('form[name="csr_form"]');
    const csrList = document.getElementById('csr-list');

    function updateCSRList() {
        const csrs = csrStorage.getAllCSRs();
        csrList.innerHTML = '';

        csrs.forEach(csr => {
            const csrElement = document.createElement('div');
            csrElement.className = 'csr-item mb-4 p-4 border rounded';
            csrElement.innerHTML = `
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold">${csr.metadata.commonName}</h3>
                        <p class="text-sm">${csr.metadata.organization} (${csr.metadata.country})</p>
                        <p class="text-sm text-gray-500">Created: ${csr.metadata.createdAt}</p>
                    </div>
                    <div>
                        <button class="copy-btn bg-blue-500 text-white px-2 py-1 rounded mr-2" 
                                data-csr="${encodeURIComponent(csr.csr)}">Copy CSR</button>
                        <button class="delete-btn bg-red-500 text-white px-2 py-1 rounded" 
                                data-id="${csr.id}">Delete</button>
                    </div>
                </div>
            `;
            csrList.appendChild(csrElement);
        });

        // Add event listeners for copy buttons
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const csrText = decodeURIComponent(this.dataset.csr);
                navigator.clipboard.writeText(csrText)
                    .then(() => alert('CSR copied to clipboard!'))
                    .catch(err => console.error('Failed to copy:', err));
            });
        });

        // Add event listeners for delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this CSR?')) {
                    csrStorage.deleteCSR(parseInt(this.dataset.id));
                    updateCSRList();
                }
            });
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            fetch('/csr/generate', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Save to local storage
                    csrStorage.saveCSR(data);
                    // Update the display
                    updateCSRList();
                    // Show success message
                    alert('CSR generated and saved successfully!');
                } else {
                    throw new Error(data.error || 'Failed to generate CSR');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
            });
        });
    }

    // Initial load of CSR list
    updateCSRList();
});