<style>
.loader {
    margin-top: 10%;
    margin-left: 50%;
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #037ecc;
    border-bottom: 16px solid #037ecc;
    border-right: 16px solid #f57c00;
    border-left: 16px solid #f57c00;
    width: 100px;
    height: 100px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
}

@keyframes  spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader-box
{
    display: block;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    position: fixed;
    opacity: 1;
    background-color: #fff;
    z-index: 999999999999;
    text-align: center;

}
.loader-box .flex-center{
    height: 100%;
    align-items: center;
    justify-content: center;
    display: flex;
    width: 100%;
    padding: 10px;
}
.fa-star
{
    color: #f8790a;
}
.fa-star-half
{
    color: #f8790a;
}
</style>

