@extends('layouts.app')
@section('title', 'Verify phone number')
@section('main-style')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/membership.css') }}" />
@endsection
@section('content')
    <div class="wrapper-container wrapper-without-top">
        <div class="membership-container membership-bg" style="background-image: url({{ asset('assets/img/jobs-bg.png') }})">
            <div class="container">
                <!-- Title In Small Screen -->
                <div class="mem-sm-title title text-center mb-3">Verify your phone number</div>
                <div class="membership-center">
                    <div class="membership-form-container">
                        <div class="membership-title title text-center mb-5">Verify your phone number</div>
                        <div class="membership-text mb-4 pt-4 text-center">
                            You will receive a message with an activation<br /> code on the number
                        </div>
                        <div class="membership-form-content">

                            <div class="form-group-row text-center mb-3">
                                <div class="number-phone-card">+{{ $phone_number }}</div>
                            </div>

                            <a class="btn btn-simple wn-btn d-block text-center mb-3" href="{{ route('account.account') }}">Wrong number?</a>
                            <form  method="post" action="{{ route('verification.phone.save') }}">
                            <div class="form-group-row form-digits mb-5">
                                <div class="membership-text mb-3">Enter 5 Digit activation code </div>
                                <div class="form-verify">
                                    <div>
                                        @csrf
                                        <input name='code[]' class='code-input' required autofocus/>
                                        <input name='code[]' class='code-input' required/>
                                        <input name='code[]' class='code-input' required/>
                                        <input name='code[]' class='code-input' required/>
                                        <input name='code[]' class='code-input' required/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-row mb-4">
                                <button type="submit" class="btn btn-bg width-fluid">Verify</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        const inputElements = [...document.querySelectorAll('input.code-input')]

        $(function(){
          $(inputElements).keypress(function(e){
            // allowed char: 1 , 2 , 3, 4, 5, N, O, A, B, C
            let allow_char = [8, 9, 13, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57];
            if(allow_char.indexOf(e.which) !== -1 ){
              return true;
            }
            else{
              return false;
            }
          });
        });

        inputElements.forEach((ele,index)=>{
            ele.addEventListener('keydown',(e)=>{
                if(e.keyCode === 8 && e.target.value==='') inputElements[Math.max(0,index-1)].focus();
            })
            ele.addEventListener('input',(e)=>{
                const [first,...rest] = e.target.value
                e.target.value = first ?? '' // first will be undefined when backspace was entered, so set the input to ""
                const lastInputBox = index===inputElements.length-1
                const didInsertContent = first!==undefined
                if(didInsertContent && !lastInputBox) {
                    // continue to input the rest of the string
                    inputElements[index+1].focus()
                    inputElements[index+1].value = rest.join('')
                    inputElements[index+1].dispatchEvent(new Event('input'))
                }
            })
        })

        // mini example on how to pull the data on submit of the form
        $("#verifyCode").on('click', function (e){
            e.preventDefault()
            const code = inputElements.map(({value})=>value).join('');
        });

        document.querySelectorAll('.code-input').forEach(inp => new Cleave(inp, {
          numeral: true,
          // numeralThousandsGroupStyle: 'thousand'
        }));
    </script>
@endsection
