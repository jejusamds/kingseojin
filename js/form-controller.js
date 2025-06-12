class FormValidator {
    constructor(form) {
        this.form = form;
    }

    // 기본 유효성 검사 규칙들
    validationRules = {
        // 필수 입력 검사
        required: (value) => {
            return value.trim() !== '';
        },
        // 전화번호 형식 검사
        tel: (value) => {
            return /^[\d-]+$/.test(value);
        },
        // 이메일 형식 검사
        email: (value) => {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        },
        clicked: (value, element) => {
            if (element.type === 'checkbox') {
                return element.checked;
            } else if (element.type === 'radio') {
                const radioGroup = this.form.querySelectorAll(`input[name="${element.name}"]`);
                return Array.from(radioGroup).some(radio => radio.checked);
            }
            return true;
        }
    };

    // 폼 전체 유효성 검사
    validateForm() {
        const requiredElements = this.form.querySelectorAll('[data-required="y"]');

        for (const element of requiredElements) {
            if (!this.validateElement(element)) {                
                return false;
            }
        }
        return true;
    }

    // 개별 요소 유효성 검사
    validateElement(element) {
        const value = element.value;
        const type = element.getAttribute('data-validate-type'); // 예: tel, email 등 형식이 정해진 항목들
        const label = element.getAttribute('data-label') || element.placeholder || '필수 항목';
        const tagType = element.getAttribute('data-tag-type');

        if (tagType === 'clicked') {
            if (!this.validationRules.clicked(value, element)) {
                this.showError(element, `${label} 선택해 주세요.`);
                return false;
            }
            return true;
        }

        // 필수 입력 검사
        if (!this.validationRules.required(value)) {
            this.showError(element, `${label} 입력해 주세요.`);
            return false;
        }

        // 추가 유효성 검사 (tel, email 등)
        if (type && this.validationRules[type]) {
            if (!this.validationRules[type](value)) {
                this.showError(element, `${label}의 형식이 올바르지 않습니다.`);
                return false;
            }
        }

        return true;
    }

    showError(element, message) {
        alert(message);
        element.focus();
    }
}

class FormSubmitter {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.validator = new FormValidator(this.form);
    }

    async submit() {
        if (!this.validator.validateForm()) {
            return false;
        }

        try {
            const response = await this.sendData();
            this.handleResponse(response);
        } catch (error) {
            console.log(error);
            alert('서버와의 통신중 오류가 발생하였습니다.');
        }
    }

    async sendData() {
        const formData = new FormData(this.form);
        const response = await fetch(this.form.action, {
            method: this.form.method || 'POST',
            body: formData
        });
        return await response.json();
    }

    handleResponse(data) {
        if (data.result == 'test') {
            console.log(data);
        }
        alert(data.msg);
        if (data.result === 'ok') {
            if (data.redirect != '') {
                location.href = data.redirect;
            }
            this.resetForm();
            // 추가 리셋. id="f_auth_number"
            //$('#f_auth_number').val('');
        }
    }

    resetForm() {
        const excludeTypes = ['hidden', 'submit', 'button'];
        
        const elements = Array.from(this.form.querySelectorAll('input, select, textarea'));
        
        elements.forEach((element) => {
            //console.log(element.type);
            if (!excludeTypes.includes(element.type)) {
                if (element.type === 'checkbox' || element.type === 'radio') {
                    const sameName = elements.filter(el => el.name === element.name);
                    element.checked = sameName[0] === element;
                } else if (element.tagName === 'SELECT') {
                    element.selectedIndex = 0;
                } else if (element.type === 'file') {
                    //file_upload_delete();
                } else {
                    element.value = '';
                }
            }
        });
    }
}

function submitForm(formId) {
    const submitter = new FormSubmitter(formId);
    submitter.submit();
}