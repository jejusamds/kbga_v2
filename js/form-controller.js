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
                const group = this.form.querySelectorAll(`input[name="${element.name}"]`);
                return Array.from(group).some(cb => cb.checked);
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

        // ■ 중복확인 버튼 바인딩
        const checkBtn = this.form.querySelector('#btn_check_id');
        if (checkBtn) {
            checkBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.checkDuplicateId();
            });
        }
    }

    // ■ 아이디 중복검사 메소드
    async checkDuplicateId() {
        const el = this.form.querySelector('#f_user_id');
        const id = el.value.trim();
        if (!id) {
            alert('아이디를 입력해주세요.');
            el.focus();
            return;
        }

        const fd = new FormData();
        fd.append('mode', 'check_id');
        fd.append('csrf_token', this.form.querySelector('input[name="csrf_token"]').value);
        fd.append('f_user_id', id);

        try {
            const res = await fetch(this.form.action, { method: this.form.method, body: fd });
            const json = await res.json();
            console.log(json);
            if (json.result === 'exist') {
                alert('이미 사용 중인 아이디입니다.');
                el.focus();
            } else {
                alert('사용 가능한 아이디입니다.');
            }
        } catch (err) {
            console.error(err);
            alert('중복확인 중 오류가 발생했습니다.');
        }
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
        console.log(data);
        if (data.result != 'ok') {
            alert(data.msg);
        }
        
        if (data.result === 'ok') {
            if (data.msg != '') {
                alert(data.msg);
            }
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
    const formEl = document.getElementById(formId);
    if (!formEl) {
        console.error(`Form with id="${formId}" not found.`);
        return;
    }
    const submitter = new FormSubmitter(formId);
    submitter.submit();
}