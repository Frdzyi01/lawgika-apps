import re

path = 'resources/views/frontend/dashboard.blade.php'
with open(path, 'r') as f:
    lines = f.readlines()

replacement = """        <div class="swiper-wrapper">
          
          <!-- Testimonial 1 -->
          <div class="swiper-slide">
            <div class="testimonial-card-items" style="height: 100%;">
              <div class="star">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="33" viewBox="0 0 44 33" fill="none"><path d="M16 16.2929L0.5 31.7929V0.5H16V16.2929ZM43.5 16.2929L28 31.7929V0.5H43.5V16.2929Z" stroke="white" /></svg>
              </div>
              <div class="client-image bg-cover" style="background-image: url('{{ asset('buyer-file/assets/img/testimonial/circle.webp') }}');">
                <div class="circle-shape"><img src="{{ asset('buyer-file/assets/img/testimonial/circle.webp') }}" alt="" loading="lazy" /></div>
              </div>
              <p>Pendampingan dari Lawgika membuat proses legalitas bisnis menjadi rapi dan sesuai tenggat waktu. Tim sangat profesional dan responsif!</p>
              <div class="client-info">
                <h4>Budi Santoso</h4>
                <span>CEO Startup Teknologi</span>
              </div>
            </div>
          </div>

          <!-- Testimonial 2 -->
          <div class="swiper-slide">
            <div class="testimonial-card-items" style="height: 100%;">
              <div class="star">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="33" viewBox="0 0 44 33" fill="none"><path d="M16 16.2929L0.5 31.7929V0.5H16V16.2929ZM43.5 16.2929L28 31.7929V0.5H43.5V16.2929Z" stroke="white" /></svg>
              </div>
              <div class="client-image bg-cover" style="background-image: url('{{ asset('buyer-file/assets/img/testimonial/circle.webp') }}');">
                <div class="circle-shape"><img src="{{ asset('buyer-file/assets/img/testimonial/circle.webp') }}" alt="" loading="lazy" /></div>
              </div>
              <p>Pengurusan HAKI dan pendaftaran merek berjalan lancar tanpa kendala. Sangat merekomendasikan Lawgika untuk urusan legal bisnis.</p>
              <div class="client-info">
                <h4>Sarah Wijaya</h4>
                <span>Founder FnB Brand</span>
              </div>
            </div>
          </div>

          <!-- Testimonial 3 -->
          <div class="swiper-slide">
            <div class="testimonial-card-items" style="height: 100%;">
              <div class="star">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="33" viewBox="0 0 44 33" fill="none"><path d="M16 16.2929L0.5 31.7929V0.5H16V16.2929ZM43.5 16.2929L28 31.7929V0.5H43.5V16.2929Z" stroke="white" /></svg>
              </div>
              <div class="client-image bg-cover" style="background-image: url('{{ asset('buyer-file/assets/img/testimonial/circle.webp') }}');">
                <div class="circle-shape"><img src="{{ asset('buyer-file/assets/img/testimonial/circle.webp') }}" alt="" loading="lazy" /></div>
              </div>
              <p>Virtual Office dari Lawgika sangat membantu kelancaran operasional perusahaan kami di zona premium dengan harga terjangkau.</p>
              <div class="client-info">
                <h4>Andi Pratama</h4>
                <span>Direktur PT. Maju Bersama</span>
              </div>
            </div>
          </div>

          <!-- Testimonial 4 -->
          <div class="swiper-slide">
            <div class="testimonial-card-items" style="height: 100%;">
              <div class="star">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="33" viewBox="0 0 44 33" fill="none"><path d="M16 16.2929L0.5 31.7929V0.5H16V16.2929ZM43.5 16.2929L28 31.7929V0.5H43.5V16.2929Z" stroke="white" /></svg>
              </div>
              <div class="client-image bg-cover" style="background-image: url('{{ asset('buyer-file/assets/img/testimonial/circle.webp') }}');">
                <div class="circle-shape"><img src="{{ asset('buyer-file/assets/img/testimonial/circle.webp') }}" alt="" loading="lazy" /></div>
              </div>
              <p>Serviced Office yang nyaman, staf yang ramah, dan lokasi strategis. Sempurna untuk aktivitas rapat dengan klien penting.</p>
              <div class="client-info">
                <h4>Lestari Ningsih</h4>
                <span>Konsultan Independen</span>
              </div>
            </div>
          </div>

          <!-- Testimonial 5 -->
          <div class="swiper-slide">
            <div class="testimonial-card-items" style="height: 100%;">
              <div class="star">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="33" viewBox="0 0 44 33" fill="none"><path d="M16 16.2929L0.5 31.7929V0.5H16V16.2929ZM43.5 16.2929L28 31.7929V0.5H43.5V16.2929Z" stroke="white" /></svg>
              </div>
              <div class="client-image bg-cover" style="background-image: url('{{ asset('buyer-file/assets/img/testimonial/circle.webp') }}');">
                <div class="circle-shape"><img src="{{ asset('buyer-file/assets/img/testimonial/circle.webp') }}" alt="" loading="lazy" /></div>
              </div>
              <p>Layanan yang diberikan Lawgika melebihi ekspektasi. Semua izin usaha selesai tepat waktu, memudahkan kami untuk berekspansi.</p>
              <div class="client-info">
                <h4>Dedi Gunawan</h4>
                <span>Direktur Eksekutif</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
"""

# replace everything between `<div class="swiper-wrapper" id="google-reviews-wrapper">` and `</section>` (inclusive of the google reviews section but stopping at the exact section end tag)
# The file structure has:
#    <div class="tesimonial-wrapper">
#      <div class="swiper testimonial-slider">
#        <div class="swiper-wrapper" id="google-reviews-wrapper">
#          ...
#        </div>
#      </div>
#    </div>
#  </div>
#</section>

# Then the script blocks.

# Let's just find the start line and replace down to the `</section>`
start_idx = -1
end_idx = -1

for i, line in enumerate(lines):
    if '<div class="swiper-wrapper" id="google-reviews-wrapper">' in line:
        start_idx = i
        break

for i in range(start_idx, len(lines)):
    if '</section>' in lines[i]:
        end_idx = i
        break

# The script blocks are after `</section>`. They start with `<!-- Google Maps API:` and go until before `</script>` ending the IIFE fetchGoogleReviews.
script_start = -1
script_end = -1
for i in range(end_idx, len(lines)):
    if '<!-- Google Maps API:' in lines[i]:
        script_start = i
        break

for i in range(script_start, len(lines)):
    if 'fetchGoogleReviews();' in lines[i]:
        # we need to find the closing </script> for this block
        for j in range(i, len(lines)):
            if '</script>' in lines[j]:
                script_end = j
                break
        break

new_lines = lines[:start_idx] + [replacement + "\n"]

# now if script block was found, we delete it
if script_start != -1 and script_end != -1:
    new_lines.extend(lines[end_idx+1:script_start]) # keep anything between </section> and <!-- Google Maps
    new_lines.extend(lines[script_end+1:]) # skip the script block
else:
    new_lines.extend(lines[end_idx+1:])

with open(path, 'w') as f:
    f.writelines(new_lines)
