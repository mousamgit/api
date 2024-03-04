editFilter(productDet,index)
{
    console.log('edit');
    this.showAttFilter =0;
    this.editForm=index;
    this.channelAttribute = [{
        id: 0,
        attribute_name: '',
        data_type: productDet.data_type_value,
        filter_type: productDet.filter_type,
        attribute: productDet.attribute_name +','+productDet.data_type_value,
        attribute_condition: productDet.attribute_condition,
        operator: productDet.op_value,
        condition_type: 'abc',
        previous_row: [],
        type:'edit'
    }];
},
<div class="editForm" v-if="showAttFilter==0 && editForm===index">

    <form @submit.prevent="submitForm">

    <div v-for="(cAttribute, index) in channelAttribute" :key="index" class="channel-condition card">

    <div>
        <label for="attribute" class="form-label">SELECT ATTRIBUTE:</label>
        <label class="delete-icon position-absolute top-0 end-0" >
            <a @click="refreshAttributeAgain">
            <i class="fa fa-times animation-mode" aria-hidden="true"></i>
        </a>
    </label>

    <select class="form-control" @change="handleChangeAttribute(index)" required="">
    <template v-for="column in columns">
        <template v-if="column.column_name == productDet.attribute_name">
            <option :value="column.column_name+ ',' +column.data_type" selected>{{column.column_name}}</option>
    </template>
    <template v-else>
        <option :value="column.column_name+ ',' +column.data_type">{{column.column_name}}</option>
</template>
</template>
</select>
<div>
    <div class="mb-3">

        <template v-if="cAttribute.data_type == 'varchar'">

            <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">
                <option value="includes" :selected="productDet.filter_type === 'includes'">includes</option>
            <option value="dont_includes" :selected="productDet.filter_type === 'dont_includes'">doesn't include</option>
        <option value="=" :selected="productDet.filter_type === '='">equal to</option>
    <option value="!=" :selected="productDet.filter_type === '!='">not equal to</option>
<option value="IS NOT NULL" :selected="productDet.filter_type === 'IS NOT NULL'">is not empty</option>
<option value="IS NULL" :selected="productDet.filter_type === 'IS NULL'">is empty</option>
</select>

<template v-if="cAttribute.filter_type == '=' || cAttribute.filter_type == '!='">
    <input type="text" v-model="cAttribute.attribute_condition" class="form-control hidden" readonly required>
        <span v-if="showManualValidationMessage==1" class="danger">Search and Tick Condition below</span>
        <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition">
        <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">
            <li v-for="(value, vindex) in attribute_values" :key="vindex" >
            <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index)">
            <label :for="'checkbox_' + vindex">{{ value }}</label>
    </li>
</ul>
</template>
<template v-else-if="cAttribute.filter_type == 'includes' || cAttribute.filter_type == 'dont_includes'">
    <input type="text" v-model="cAttribute.attribute_condition" class="form-control"  required>
</template>
</template>
<template v-if="cAttribute.data_type != 'varchar'">

    <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">
        <option value="=">equal to</option>
        <option value="!=">not equal to</option>
        <option value=">">is greater than</option>
        <option value="<">is less than</option>
        <option value="between">range</option>
        <option value="IS NOT NULL">is not empty</option>
        <option value="IS NULL">is empty</option>
    </select>
    <template v-if="cAttribute.filter_type == 'between'">
        <div class="row">
            <div class="col-md-6">
                <input type="text" v-model="cAttribute.rangeFrom" class="form-control" placeholder="From" required>
            </div>
            <div class="col-md-6">
                <input type="text" v-model="cAttribute.rangeTo" class="form-control" placeholder="To" required>
            </div>
        </div>
    </template>
    <template v-if="cAttribute.data_type != 'varchar' && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')">
        <input type="text" v-model="cAttribute.attribute_condition" class="form-control" readonly  required>
            <span v-if="showManualValidationMessage==1" class="alert-danger">Search and Tick Condition below</span>
            <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition">
            <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">
                <li v-for="(value, vindex) in attribute_values" :key="vindex" >
                <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index)">
                <label :for="'checkbox_' + vindex">{{ value }}</label>
        </li>
    </ul>
</template>
<template v-else-if="cAttribute.filter_type == '>' || cAttribute.filter_type == '<'">
    <input type="text" v-model="cAttribute.attribute_condition" class="form-control"  required>
</template>
</div>
<div class="submit-form" v-if="cAttribute.attribute!=''">
    <button type="submit" class="btn btn-primary mt-3">Apply Filters</button>
</div>
</div>

</form>
</div>