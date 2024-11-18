                              <div class="row g-3">
                                  <div class="col-lg-12">
                                      <div class="col-lg-12">
                                          <div class="form-group">
                                              <label for="body" class="form-label">Question</label>
                                              <div class="form-control-wrap">
                                                  <textarea cols="80" id="editor1" name="question" rows="10">{{$faq->question ?? ''}}</textarea>
                                                  <script>
                                                      CKEDITOR.replace('editor1', {
                                                          height: 260,
                                                          width: 700,
                                                          removeButtons: 'PasteFromWord'
                                                      });
                                                  </script>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="col-lg-12">
                                          <div class="form-group">
                                              <label for="body" class="form-label">Answer</label>
                                              <div class="form-control-wrap">
                                                  <textarea cols="80" id="editor2" name="answer" rows="10">{{$faq->answer ?? ''}}</textarea>
                                                  <script>
                                                      CKEDITOR.replace('editor2', {
                                                          height: 260,
                                                          width: 700,
                                                          removeButtons: 'PasteFromWord'
                                                      });
                                                  </script>
                                              </div>
                                          </div>
                                      </div>
                                  </div>