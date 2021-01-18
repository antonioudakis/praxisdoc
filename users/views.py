from django.shortcuts import render, redirect
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from .models import UserProfile
from .forms import UserRegisterForm, UserUpdateForm, UserProfileForm, StudentProfileForm

def registration(request):
	return render(request,'users/registration.html', {'title':'Εγγραφή'})

def register_student(request):
	if request.method == 'POST':
		u_form = UserRegisterForm(request.POST)
		# p_form = UserProfileForm(request.POST)
		p1_form = StudentProfileForm(request.POST)
		print("check for valid forms")
		if u_form.is_valid() and p1_form.is_valid():
			print("all forms are valid")
			user = u_form.save()
			username = u_form.cleaned_data.get('username')
			profile = UserProfile(user=user, is_student = True)
			#profile = p_form.save(commit=False)
			#profile.user = user
			#profile.is_student = True
			profile.save()
			student_profile = p1_form.save(commit=False)
			student_profile.user = user
			student_profile.save()
			messages.success(request,f'O λογαριασμός δημιουργήθηκε. Μπορείτε τώρα να συνδεθείτε')
			return redirect('login')
	else:
		u_form = UserRegisterForm()
		#p_form = UserProfileForm()
		p1_form = StudentProfileForm()

	context = {
		'title':'Εγγραφή Φοιτητή',
		'role':'student',
		'u_form':u_form,
		#'p_form':p_form,
		'p1_form':p1_form,
	}

	return render(request,'users/register.html', context)

@login_required
def profile(request):
	if request.method == 'POST':
		u_form = UserUpdateForm(request.POST, instance=request.user)
		p_form = UserProfileForm(request.POST, instance=request.user.userprofile)
		if request.user.userprofile.is_student:
			p1_form = StudentProfileForm(request.POST, request.FILES, instance=request.user.studentprofile)
		else:
			pass
		print("before check valid")
		if u_form.is_valid() and p_form.is_valid() and p1_form.is_valid():
			print("after check valid")
			u_form.save()
			p_form.save()
			p1_form.save()
			messages.success(request, f'Το προφίλ σας ενημερώθηκε')
			return redirect('users:profile')
		else:
			print("this is not valid")

	else:
		u_form = UserUpdateForm(instance=request.user)
		p_form = UserProfileForm(instance=request.user.userprofile)
		if request.user.userprofile.is_student:
			p1_form = StudentProfileForm(instance=request.user.studentprofile)
		else:
			pass

	if request.user.userprofile.is_student:
		role = 'student'
		title = 'Ενημέρωση Προφίλ Φοιτητή'
	else:
		role = 'user'
		title = 'Ενημέρωση Προφίλ Χρήστη'

	context = {
		'title':title,
		'role':role,
		'u_form':u_form,
		'p_form':p_form,
		'p1_form':p1_form
	}
	return render(request,'users/profile.html', context)
