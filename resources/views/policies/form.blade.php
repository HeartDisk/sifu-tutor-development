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
                <option value="Parent" {{ (isset($faq) && $faq->type == 'Parent') ? 'selected' : '' }}>Parent</option>
                <option value="Tutor" {{ (isset($faq) && $faq->type == 'Tutor') ? 'selected' : '' }}>Tutor</option>
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
