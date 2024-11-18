<div class="row g-3">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="question" class="form-label">Question</label>
            <textarea id="editor1" name="question">{{ $faq->question ?? '' }}</textarea>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group">
            <label for="answer" class="form-label">Answer</label>
            <textarea id="editor2" name="answer">{{ $faq->answer ?? '' }}</textarea>
        </div>
    </div>

    <!-- Type Field -->
    <div class="col-lg-12">
        <div class="form-group">
            <label for="type" class="form-label">Type</label>
            <select name="type" class="form-control">
                <option value="">Select Type</option>
                <option value="Parent" {{ (isset($faq) && $faq->type == 'parent') ? 'selected' : '' }}>Parent</option>
                <option value="Tutor" {{ (isset($faq) && $faq->type == 'tutor') ? 'selected' : '' }}>Tutor</option>
            </select>
        </div>
    </div>

    <!-- Category Field -->
    <div class="col-lg-12">
        <div class="form-group">
            <label for="category" class="form-label">Category</label>
            <select name="category" class="form-control">
                <option value="">Select Category</option>
                <option value="Application & Registration" {{ (isset($faq) && $faq->category == 'Application & Registration') ? 'selected' : '' }}>Application & Registration</option>
                <option value="Payment" {{ (isset($faq) && $faq->category == 'Payment') ? 'selected' : '' }}>Payment</option>
                <option value="Others" {{ (isset($faq) && $faq->category == 'Others') ? 'selected' : '' }}>Others</option>
            </select>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.querySelector('select[name="type"]');
    const categorySelect = document.querySelector('select[name="category"]');

    const parentCategories = [
        { value: 'Application & Registration', text: 'Application & Registration' }
    ];

    const tutorCategories = [
        { value: 'GETTING STARTED', text: 'GETTING STARTED' },
        { value: 'APPLYING FOR REQUESTS AND SCHEDULING', text: 'APPLYING FOR REQUESTS AND SCHEDULING' },
        { value: 'PAYMENT AND FEES', text: 'PAYMENT AND FEES' },
        { value: 'CLASS AND SESSION MANAGEMENT', text: 'CLASS AND SESSION MANAGEMENT' },
        { value: 'WORKING AS A TUTOR-PARTNER', text: 'WORKING AS A TUTOR-PARTNER' },
        { value: 'LEAVING SIFUTUTOR', text: 'LEAVING SIFUTUTOR' }
    ];

    function updateCategoryOptions() {
        // Clear existing options
        categorySelect.innerHTML = '<option value="">Select Category</option>';

        let categories = [];
        if (typeSelect.value === 'Parent') {
            categories = parentCategories;
        } else if (typeSelect.value === 'Tutor') {
            categories = tutorCategories;
        }

        // Add new options
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.value;
            option.text = category.text;
            categorySelect.appendChild(option);
        });
    }

    // Initial category update
    updateCategoryOptions();

    // Update categories when type changes
    typeSelect.addEventListener('change', updateCategoryOptions);
});
</script>