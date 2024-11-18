                                    <div class="row g-3">

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="firstname" class="form-label">Page To Open</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-control notificationType" name="page">
                                                        <option value="">--- Select ---</option>
                                                        <option value="dashboard">Dashboard</option>
                                                        <option value="profile">Profile</option>
                                                        <option value="cumulative_commission">Cumulative Commission</option>
                                                        <option value="payment_history">Payment History</option>
                                                        <option value="inbox">Inbox</option>
                                                        <option value="job_ticket_list">Job Ticket List</option>
                                                        <option value="class_schedule_list">Class Schedule List</option>
                                                        <option value="submission_history">Submission History</option>
                                                        <option value="student_list">Student List</option>
                                                        <option value="pending_actions">Pending Actions</option>
                                                        <option value="faq">Faq</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="firstname" class="form-label">Subject</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="subject" value="{{$notification->subject ?? ''}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="firstname" class="form-label">Message</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="message" value="{{$notification->message ?? ''}}">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="firstname" class="form-label">Push Time <span>(24hrs format)</span> </label>
                                                <div class="form-control-wrap">
                                                    <input type="time" class="form-control" name="time" value="{{$notification->time ?? ''}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="firstname" class="form-label">Push Type</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-control notificationType" name="type">
                                                        <option value="">--- Select ---</option>
                                                        <option value="one_time">One Time</option>
                                                        <option value="recurring">Recurring</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="firstname" class="form-label">Push Date</label>
                                                <div class="form-control-wrap">
                                                    <input type="date" class="form-control" name="date" value="{{$notification->date ?? ''}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="firstname" class="form-label">Remark</label>
                                                <div class="form-control-wrap">
                                                    <textarea  id="" cols="90" rows="10" name="remark">{{$notification->remark ?? ''}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
