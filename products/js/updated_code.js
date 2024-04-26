
<!--                                        <div v-if="productDet.op_value== 'OR' && productDet.id != productDetails[0].id && showAttributeMid==productDetails[index-1].id">-->
<!--                                            &lt;!&ndash;{{showAttributeMid}} '=' {{productDetails[index-1].id}}&ndash;&gt;-->
<!--                                            -->
<!--                                            <form @submit.prevent="submitForm">-->
<!--                                                <span>-&#45;&#45;&#45;&#45; {{op_show_value}} -&#45;&#45;&#45;&#45;&#45;&#45;</span> -->
<!--                                                <div class="row">-->
<!--                                                    &lt;!&ndash; Bootstrap Form Group Component &ndash;&gt;-->
<!--                                                    <div class="form-group filter-clauses" >-->
<!--                                                        <fieldset>-->
<!--                                                            <div v-for="(cAttribute, index) in channelAttribute" :key="index" class="channel-condition">-->
<!--                                                                <div class="row mb-3">-->
<!--                                                                    <div class="col-md-12" v-if="showAttribute==1">-->
<!--                                                                        &lt;!&ndash; <label for="attribute" class="form-label">SELECT ATTRIBUTE:</label>&ndash;&gt;-->
<!--                                                                        <div class="col-md-12 position-relative">-->
<!--                                                                            <div class="d-flex justify-content-between align-items-center">-->
<!--                                                                                <label for="attribute" class="form-label">SELECT ATTRIBUTE:</label>-->
<!--                                                                                <label class="delete-icon" style=" position: absolute;top: -10px;  right: 0;">-->
<!--                                                                                    <a @click="refreshAttributeAgain">-->
<!--                                                                                        <i class="btn btn-danger fas fa-trash-alt"></i>-->
<!--                                                                                    </a>-->
<!--                                                                                </label>-->
<!--                                                                            </div>-->
<!--                                                                        </div>-->
<!--                                                                        <div class="mb-3">-->
<!--                                                                            <select v-model="cAttribute.attribute" class="form-control" @change="handleChangeAttribute(index)" required>-->
<!--                                                                                <option v-for="column in columns" :key="column.column_name" :value="column.column_name + ',' + column.data_type">-->
<!--                                                                                    {{ column.column_name }}-->
<!--                                                                                </option>-->
<!--                                                                            </select>-->
<!--                                                                        </div>-->
<!--                                                                    </div>-->
<!--                                                                    -->
<!--                                                                    <div class="col-md-12" v-if="cAttribute.attribute != ''">-->
<!--                                                                        <div class="mb-3">-->
<!--                                                                            <template v-if="cAttribute.data_type == 'varchar'">-->
<!--                                                                                <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">-->
<!--                                                                                    <option value="includes">includes</option>-->
<!--                                                                                    <option value="dont_includes">doesn't include</option>-->
<!--                                                                                    <option value="=">equal to</option>-->
<!--                                                                                    <option value="!=">not equal to</option>-->
<!--                                                                                    <option value="IS NOT NULL">is not empty</option>-->
<!--                                                                                    <option value="IS NULL">is empty</option>-->
<!--                                                                                </select>-->
<!--                                                                                <template v-if="cAttribute.filter_type == '=' || cAttribute.filter_type == '!='">-->
<!--                                                                                     <input type="text" v-model="cAttribute.attribute_condition" class="form-control hidden" readonly required>-->
<!--                                                                                     <span v-if="showManualValidationMessage==1" class="danger">Search and Tick Condition below</span>-->
<!--                                                                                     <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition" >-->
<!--                                                                                     <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">-->
<!--                                                                                       <li v-for="(value, vindex) in attribute_values" :key="vindex" >-->
<!--                                                                                          <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index)">-->
<!--                                                                                          <label :for="'checkbox_' + vindex">{{ value }}</label>-->
<!--                                                                                        </li>    -->
<!--                                                                                     </ul>-->
<!--                                                                                </template>-->
<!--                                                                                <template v-else-if="cAttribute.filter_type == 'includes' || cAttribute.filter_type == 'dont_includes'">-->
<!--                                                                                     <input type="text" v-model="cAttribute.attribute_condition" class="form-control"  required>    -->
<!--                                                                                </template>-->
<!--                                                                            </template>-->
<!--                                                                            <template v-if="cAttribute.data_type != 'varchar'">-->
<!--                                                                                <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">-->
<!--                                                                                    <option value="=">equal to</option>-->
<!--                                                                                    <option value="!=">not equal to</option>-->
<!--                                                                                    <option value=">">is greater than</option>-->
<!--                                                                                    <option value="<">is less than</option>-->
<!--                                                                                    <option value="between">range</option>-->
<!--                                                                                    <option value="IS NOT NULL">is not empty</option>-->
<!--                                                                                    <option value="IS NULL">is empty</option>-->
<!--                                                                                </select>-->
<!--                                                                                <template v-if="cAttribute.filter_type == 'between'">-->
<!--                                                                                    <div class="row">-->
<!--                                                                                        <div class="col-md-6">-->
<!--                                                                                            <input type="text" v-model="cAttribute.rangeFrom" class="form-control" placeholder="From" required>-->
<!--                                                                                        </div>-->
<!--                                                                                        <div class="col-md-6">-->
<!--                                                                                            <input type="text" v-model="cAttribute.rangeTo" class="form-control" placeholder="To" required>-->
<!--                                                                                        </div>-->
<!--                                                                                    </div>-->
<!--                                                                        </div>-->
<!--                                                                        </template>-->
<!--                                                                        <template v-if="cAttribute.data_type != 'varchar' && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')">-->
<!--                                                                            <input type="text" v-model="cAttribute.attribute_condition"  class="form-control hidden" readonly required>-->
<!--                                                                            <span v-if="showManualValidationMessage==1" class="danger">Search and Tick Condition below</span>-->
<!--                                                                            <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition" >-->
<!--                                                                            <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">-->
<!--                                                                                <li v-for="(value, vindex) in attribute_values" :key="vindex" >-->
<!--                                                                                          <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index)">-->
<!--                                                                                          <label :for="'checkbox_' + vindex">{{ value }}</label>-->
<!--                                                                                </li> -->
<!--                                                                            </ul>-->
<!--                                                                        </template>-->
<!--                                                                        <template v-else-if="cAttribute.filter_type == '>' || cAttribute.filter_type == '<'">-->
<!--                                                                          <input type="text" v-model="cAttribute.attribute_condition" class="form-control"  required>    -->
<!--                                                                        </template>-->
<!--                                                                    </div>-->
<!--                                                                    <div class="submit-form" v-if="cAttribute.attribute!=''">-->
<!--                                                                        <button type="submit" class="btn btn-primary mt-3">Apply Filters</button>-->
<!--                                                                    </div>-->
<!--                                                                </div>-->
<!--                                                                <div class="submit-form">-->
<!--                                                                </div>-->
<!--                                                            </div>-->
<!--                                                            <div class="operators" v-if="productDetails.length>0 && channelAttribute.length==0">-->
<!--                                                                <a class="btn white-btn" @click="addChannelCondition('AND','normal',productDetails[productDetails.length - 1])" data-test-id="and">-->
<!--                                                                    <strong>AND</strong>-->
<!--                                                                </a>-->
<!--                                                                <a class="btn white-btn" @click="addChannelCondition('OR','group',productDetails[productDetails.length - 1])" data-test-id="or">-->
<!--                                                                    <strong>OR</strong>-->
<!--                                                                </a>-->
<!--                                                            </div>-->
<!--                                                        </fieldset>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </form>-->
<!--                                        </div>-->