from django.shortcuts import render, redirect
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.core.files.storage import FileSystemStorage
#from .models import UserProfile
from django.contrib.auth.models import User
from .models import StudentFileID,StudentFileEFKA,StudentFileIBAN, StudentFileDOY
from .forms import UserRegisterForm, UserUpdateForm, UserProfileForm, StudentProfileForm, StudentFileIDForm

def registration(request):
	return render(request,'users/registration.html', {'title':'Εγγραφή'})

def register_student(request):
	if request.method == 'POST':
		u_form = UserRegisterForm(request.POST)
		p_form = UserProfileForm(request.POST)
		p1_form = StudentProfileForm(request.POST)
		if u_form.is_valid() and p1_form.is_valid() and p1_form.is_valid():
			user = u_form.save()
			username = u_form.cleaned_data.get('username')
			#profile = UserProfile(user=user, is_student = True)
			profile = p_form.save(commit=False)
			profile.user = user
			profile.is_student = True
			profile.save()
			student_profile = p1_form.save(commit=False)
			student_profile.user = user
			student_profile.save()
			messages.success(request,f'O λογαριασμός δημιουργήθηκε. Μπορείτε τώρα να συνδεθείτε')
			return redirect('login')
	else:
		u_form = UserRegisterForm()
		p_form = UserProfileForm()
		p1_form = StudentProfileForm()

	context = {
		'title':'Εγγραφή Φοιτητή',
		'role':'student',
		'u_form':u_form,
		'p_form':p_form,
		'p1_form':p1_form,
	}

	return render(request,'users/register.html', context)

@login_required
def profile(request):
	if request.method == 'POST':
		u_form = UserUpdateForm(request.POST, instance=request.user)
		p_form = UserProfileForm(request.POST, instance=request.user.userprofile)
		if request.user.userprofile.is_student:
			p1_form = StudentProfileForm(request.POST, instance=request.user.studentprofile)
		else:
			pass
		if u_form.is_valid() and p_form.is_valid() and p1_form.is_valid():
			u_form.save()
			p_form.save()
			p1_form.save()
			messages.success(request, f'Το προφίλ σας ενημερώθηκε')
			return redirect('users:profile')

	else:
		u_form = UserUpdateForm(instance=request.user)
		p_form = UserProfileForm(instance=request.user.userprofile)
		if request.user.userprofile.is_student:
			p1_form = StudentProfileForm(instance=request.user.studentprofile)

	if request.user.userprofile.is_student:
		role = 'student'
		title = 'Ενημέρωση Προφίλ Φοιτητή'
		context = {
			'title':title,
			'role':role,
			'u_form':u_form,
			'p_form':p_form,
			'p1_form':p1_form
		}
	else:
		role = 'user'
		title = 'Ενημέρωση Προφίλ Χρήστη'
		context = {
			'title':title,
			'role':role,
			'u_form':u_form,
			'p_form':p_form,
		}

	
	return render(request,'users/profile.html', context)

@login_required
def upload_pdf(request):
	if request.method == 'POST':
		form = StudentDocForm(request.POST, request.FILES)
		if form.is_valid():
			form.save()
			return redirect('book_list')
	else:
		form = StudentDocForm()
	return render(request, 'dashboard/upload_book.html', {'form': form})

@login_required
def upload_fileID(request):
	if request.method == 'POST':
		try:
			uploaded_file = request.FILES['documentID']
			#fs = FileSystemStorage(location='./media/students/file1/', base_url='/media/students/file1/')
			fs = FileSystemStorage()
			name = fs.save(uploaded_file.name, uploaded_file)
			#studentFile1 = StudentFile1.objects.get(user=request.user)
			#print(name)
			#print(fs.url(name))
			studentFileID = StudentFileID(user=request.user, fileID=name)
			studentFileID.save()
			#print(studentFile1.file1)
			#print(studentFile1.file1.url)
		except Exception as e:
			#print(type(e))
			messages.warning(request, f'Δεν επιλέξατε αρχείο')
	return redirect('dashboard:index')

@login_required
def delete_fileID(request, pk):
	if request.method == 'POST':
		fileID = StudentFileID.objects.get(pk=pk)
		fileID.delete()
	return redirect('dashboard:index')

@login_required
def upload_fileEFKA(request):
	if request.method == 'POST':
		try:
			uploaded_file = request.FILES['documentEFKA']
			#fs = FileSystemStorage(location='./media/students/file1/', base_url='/media/students/file1/')
			fs = FileSystemStorage()
			name = fs.save(uploaded_file.name, uploaded_file)
			#studentFile1 = StudentFile1.objects.get(user=request.user)
			#print(name)
			#print(fs.url(name))
			studentFileEFKA = StudentFileEFKA(user=request.user, fileEFKA=name)
			studentFileEFKA.save()
			#print(studentFile1.file1)
			#print(studentFile1.file1.url)
		except Exception as e:
			#print(type(e))
			messages.warning(request, f'Δεν επιλέξατε αρχείο')
	return redirect('dashboard:index')

@login_required
def delete_fileEFKA(request, pk):
	if request.method == 'POST':
		fileEFKA = StudentFileEFKA.objects.get(pk=pk)
		fileEFKA.delete()
	return redirect('dashboard:index')

@login_required
def upload_fileIBAN(request):
	if request.method == 'POST':
		try:
			uploaded_file = request.FILES['documentIBAN']
			#fs = FileSystemStorage(location='./media/students/file1/', base_url='/media/students/file1/')
			fs = FileSystemStorage()
			name = fs.save(uploaded_file.name, uploaded_file)
			#studentFile1 = StudentFile1.objects.get(user=request.user)
			#print(name)
			#print(fs.url(name))
			studentFileIBAN = StudentFileIBAN(user=request.user, fileIBAN=name)
			studentFileIBAN.save()
			#print(studentFile1.file1)
			#print(studentFile1.file1.url)
		except Exception as e:
			#print(type(e))
			messages.warning(request, f'Δεν επιλέξατε αρχείο')
	return redirect('dashboard:index')

@login_required
def delete_fileIBAN(request, pk):
	if request.method == 'POST':
		fileIBAN = StudentFileIBAN.objects.get(pk=pk)
		fileIBAN.delete()
	return redirect('dashboard:index')

@login_required
def upload_fileDOY(request):
	if request.method == 'POST':
		try:
			uploaded_file = request.FILES['documentDOY']
			#fs = FileSystemStorage(location='./media/students/file1/', base_url='/media/students/file1/')
			fs = FileSystemStorage()
			name = fs.save(uploaded_file.name, uploaded_file)
			#studentFile1 = StudentFile1.objects.get(user=request.user)
			#print(name)
			#print(fs.url(name))
			studentFileDOY = StudentFileDOY(user=request.user, fileDOY=name)
			studentFileDOY.save()
			#print(studentFile1.file1)
			#print(studentFile1.file1.url)
		except Exception as e:
			#print(type(e))
			messages.warning(request, f'Δεν επιλέξατε αρχείο')
	return redirect('dashboard:index')

@login_required
def delete_fileDOY(request, pk):
	if request.method == 'POST':
		fileDOY = StudentFileDOY.objects.get(pk=pk)
		fileDOY.delete()
	return redirect('dashboard:index')


