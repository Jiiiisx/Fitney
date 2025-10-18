// Add Food Handler with dual options - FIXED VERSION
document.addEventListener('DOMContentLoaded', function() {
    const foodForm = document.getElementById('addFoodForm');
    const foodFormMessage = document.getElementById('foodFormMessage');
    const foodSelectionType = document.getElementById('foodSelectionType');
    const existingFoodSection = document.getElementById('existingFoodSection');
    const customFoodSection = document.getElementById('customFoodSection');
    const existingFoodSelect = document.getElementById('existingFoodSelect');
    const foodQuantity = document.getElementById('foodQuantity');
    const foodDate = document.getElementById('foodDate');
    const foodSearch = document.getElementById('foodSearch');

    // Set today's date as default
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('foodDate').value = today;

    // Initial load of existing foods
    loadExistingFoods();

    // Toggle between existing and custom food
    foodSelectionType.addEventListener('change', function() {
        if (this.value === 'existing') {
            existingFoodSection.style.display = 'block';
            customFoodSection.style.display = 'none';
        } else {
            existingFoodSection.style.display = 'none';
            customFoodSection.style.display = 'block';
        }
    });

    // Load existing foods
    function loadExistingFoods() {
        fetch('get_foods.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.foods.length > 0) {
                    existingFoodSelect.innerHTML = '<option value="">-- Select Food --</option>';
                    data.foods.forEach(food => {
                        const option = document.createElement('option');
                        option.value = food.id;
                        option.textContent = `${food.nama_makanan} (${food.kalori} kcal, ${food.porsi})`;
                        option.dataset.calories = food.kalori;
                        option.dataset.portion = food.porsi;
                        option.dataset.category = food.kategori;
                        existingFoodSelect.appendChild(option);
                    });
                } else {
                    existingFoodSelect.innerHTML = '<option value="">No foods available</option>';
                }
            })
            .catch(error => {
                console.error('Error loading foods:', error);
                existingFoodSelect.innerHTML = '<option value="">Error loading foods</option>';
            });
    }

    // Search functionality for existing foods
    foodSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const options = existingFoodSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.textContent.toLowerCase().includes(searchTerm) || searchTerm === '') {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });

    // Form submission
    foodForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const selectionType = foodSelectionType.value;
        let formData = {};

        if (selectionType === 'existing') {
            const selectedOption = existingFoodSelect.options[existingFoodSelect.selectedIndex];
            
            if (!existingFoodSelect.value) {
                showFoodMessage('Please select a food', 'error');
                return;
            }

            formData = {
                food_id: existingFoodSelect.value,
                quantity: parseInt(foodQuantity.value) || 1,
                consumption_date: foodDate.value,
                food_name: selectedOption.textContent.split(' (')[0],
                calories: parseFloat(selectedOption.dataset.calories) || 0
            };
        } else {
            // Custom food
            const foodName = document.getElementById('customFoodName').value.trim();
            const calories = parseFloat(document.getElementById('customCalories').value);
            const portion = document.getElementById('customPortion').value.trim();
            const category = document.getElementById('customCategory').value;

            if (!foodName || !calories || !portion || !category) {
                showFoodMessage('Please fill all fields', 'error');
                return;
            }

                        formData = {
                is_custom_food: true,
                food_name: foodName,
                calories: calories,
                portion: portion,
                category: category,
                quantity: parseInt(foodQuantity.value) || 1,
                consumption_date: foodDate.value
            };
        }

        if (!formData.consumption_date) {
            showFoodMessage('Please select a date', 'error');
            return;
        }

        // Submit data
        fetch('save_consumed_food.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showFoodMessage('Food added successfully!', 'success');
                resetFoodForm();
                refreshFoodList();
                // Trigger calorie summary update
                if (typeof updateCalorieSummary === 'function') {
                    updateCalorieSummary();
                }
                // Dispatch custom event for other components
                document.dispatchEvent(new CustomEvent('foodAdded'));
            } else {
                showFoodMessage(data.message || 'Error adding food', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFoodMessage('Network error. Please try again.', 'error');
        });
    });

    function showFoodMessage(message, type) {
        foodFormMessage.textContent = message;
        foodFormMessage.style.color = type === 'success' ? '#28a745' : '#dc3545';
    }

    function resetFoodForm() {
        foodForm.reset();
        document.getElementById('foodDate').value = today;
        foodFormMessage.textContent = '';
        existingFoodSection.style.display = 'block';
        customFoodSection.style.display = 'none';
    }

    function refreshFoodList() {
        // Refresh the consumed foods list
        fetch('get_consumed_foods.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.consumed_foods.length > 0) {
                    const tableBody = document.getElementById('consumedFoodDetailsTableBody');
                    tableBody.innerHTML = '';
                    
                    data.consumed_foods.forEach(food => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${food.nama_makanan}</td>
                            <td>${food.quantity}</td>
                            <td>${food.consumption_date}</td>
                            <td>${food.created_at}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                }
            })
            .catch(error => console.error('Error refreshing food list:', error));
    }
});
