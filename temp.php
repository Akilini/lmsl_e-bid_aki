<!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Status</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select class="form-control custom-select-value" name="txtstatus" id="txtstatus" required >
                                                                    <option>-- Select --</option>
                                                                    <option>Active</option>
                                                                    <option>Expaired</option>
                                                                </select>
                                                                <!-- <input type="text" name="txtstatus" id="txtstatus" class="form-control" required />-->
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Create By</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtcreate_by" id="txtcreate_by" class="form-control" required>
                                                                    <?php
                                                                    $sql_load="SELECT staff_id, name FROM staff WHERE staff_id='$system_user_id'";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["staff_id"].'">'.$row_load["name"].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <!-- <input type="text" name="txtcreate_by" id="txtcreate_by" class="form-control" required /> -->  
                                                            </div>
                                                            <!-- One Column End-->                                                                 
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->